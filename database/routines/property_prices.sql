-- Two queries because in our DB Price and Monthly Fee might have different currencies

UPDATE property
SET eur_price = price
WHERE price_currency = 'EUR';

UPDATE property
SET eur_monthly_fee = monthly_fee
WHERE monthly_fee_currency = 'EUR';

UPDATE property
SET property.price_currency_id               = (SELECT id
                                                FROM currencies
                                                WHERE currencies.currency = property.price_currency),
  property.monthly_fee_currency_id           = (SELECT id
                                                FROM currencies
                                                WHERE currencies.currency = property.monthly_fee_currency),
  property.property_tax_currency_id          = (SELECT id
                                                FROM currencies
                                                WHERE currencies.currency = property.property_tax_currency),
  property.personal_property_tax_currency_id = (SELECT id
                                                FROM currencies
                                                WHERE currencies.currency = property.personal_property_tax_currency),
  property.sold_price_currency_id            = (SELECT id
                                                FROM currencies
                                                WHERE currencies.currency = property.sold_price_currency);