<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Stripe\Event as StripeEvent;
use Exception, Response;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use App\Models\Invoice;
use App\User;

class StripeWebhookController extends CashierController
{
    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);

        if (! $this->isInTestingEnvironment() && ! $this->eventExistsOnStripe($payload['id'])) {
            return;
        }
        $method = 'handle'.studly_case(str_replace('.', '_', $payload['type']));

        if (method_exists($this, $method)) {
            return $this->{$method}($payload);
        } else {
            return $this->missingMethod();
        }
    }
    
    public function handleInvoiceCreated($payload)
    {
        $userStripeId = $payload['data']['object']['customer'];
        $data['user'] = User::where('stripe_id', $userStripeId)->first();
    
        //send Invoice disabled    
        if($data['user'])
        {
            //$data['agency'] = $data['user']->agency;
            //$data['payload'] = $payload;
            //$invoiceId = $payload['data']['object']['id'];
            //$data['invoice'] = Invoice::where('stripe_invoice_id', $invoiceId)->first();
            /*\Mail::send('emails.payment.invoice', $data, function($m) use($data){
                $m->from('miparo@miparo.com');
                $m->to($data['user']->email, $data['user']->name)->subject('Miparo Invoice!');
            });*/
        }
    }
  
  protected function handleChargeRefounded(array $payload)
  {
    return $this->hadleFailRefoundDeleted($payload);
  }
  
  
  protected function handleChargeFailed(array $payload)
  {
    return $this->hadleFailRefoundDeleted($payload);
  }
  
  
  protected function handleCustomerSubscriptionDeleted(array $payload)
  {
      return $this->hadleFailRefoundDeleted($payload);
  }
  
  private function hadleFailRefoundDeleted($payload){
    $user = $this->getUserByStripeId($payload['data']['object']['customer']);
    if ($user) {
        $user->subscriptions->filter(function ($subscription) use ($payload) {
            return $subscription->stripe_id === $payload['data']['object']['id'];
        })->each(function ($subscription) use ($user) {
            $subscription->markAsCancelled();
            $user->package_id = 1;
            $user->save();
        });
    }

    return new Response('Webhook Handled', 200);  
  }
  
  /**
   * Verify with Stripe that the event is genuine.
   *
   * @param  string  $id
   * @return bool
   */
  protected function eventExistsOnStripe($id)
  {
      try {
          return ! is_null(StripeEvent::retrieve($id, config('services.stripe.secret')));
      } catch (Exception $e) {
          return false;
      }
  }

  /**
   * Verify if cashier is in the testing environment.
   *
   * @return bool
   */
  protected function isInTestingEnvironment()
  {
      return getenv('CASHIER_ENV') === 'testing';
  }
  
  
  /**
   * Get the billable entity instance by Stripe ID.
   *
   * @param  string  $stripeId
   * @return \Laravel\Cashier\Billable
   */
  protected function getUserByStripeId($stripeId)
  {
      $model = getenv('STRIPE_MODEL') ?: config('services.stripe.model');

      return (new $model)->where('stripe_id', $stripeId)->first();
  }
    
}