<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function uploadFileToS3($uploadFile, $fileNameWithPath, $oldfileNameWithPath = null)
    {
        $fileNameWithPath = str_replace(' ', '-', $fileNameWithPath);
        Storage::disk('s3')->put($fileNameWithPath, file_get_contents($uploadFile), 'public');

        if ($oldfileNameWithPath)
            $this->s3Delete($oldfileNameWithPath);

        return '//cdn.miparo.com/' . $fileNameWithPath;
    }

    public function uploadFileToS3Bytes($file, $fileNameWithPath, $oldfileNameWithPath = null)
    {
        $fileNameWithPath = str_replace(' ', '-', $fileNameWithPath);
        Storage::disk('s3')->put($fileNameWithPath, $file, 'public');

        if ($oldfileNameWithPath)
            $this->s3Delete($oldfileNameWithPath);

        return '//cdn.miparo.com/' . $fileNameWithPath;
    }

    public function s3Delete($filePath)
    {
        $filePath = str_replace('//cdn.miparo.com/', '', $filePath);
        if ($filePath)
            Storage::disk('s3')->delete($filePath);
    }

    public function s3Exists($filePath)
    {
        $filePath = str_replace('//cdn.miparo.com/', '', $filePath);
        return Storage::disk('s3')->exists($filePath);
    }

    public static function file_get_contents_curl($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2); //timeout in seconds
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

	
	public function file_get_contents_cached($url,$backInTime = 1087000)
		{
			@mkdir(sys_get_temp_dir() . "/imagecache/");
			
			$cache_file = sys_get_temp_dir() . "/imagecache/" . md5($url);
			
			if (file_exists($cache_file))
			{
				if (filesize($cache_file) == 0)
				{
					$data= file_get_contents($url);
					$fp = fopen($cache_file, 'w');
					fwrite($fp, $data);
					fclose($fp);
				}
				else
				{
					$last_changed = filemtime($cache_file);
					if($last_changed < (time()-$backInTime))
					{
						$data= file_get_contents($url);

						if ( strlen($data) < 10)
						{
							$handle = fopen($cache_file, "r");
							$data = fread($handle, filesize($cache_file));

							$fp = fopen($cache_file, 'w');
							fwrite($fp, $data);
							fclose($fp);
						}
						else
						{
							$fp = fopen($cache_file, 'w');
							fwrite($fp, $data);
							fclose($fp);
						}
					}
					else
					{
						$handle = fopen($cache_file, "r");
						$data = fread($handle, filesize($cache_file));
					}
				}
			}
			else
			{
				$data= file_get_contents($url);
				$fp = fopen($cache_file, 'w');
				fwrite($fp, $data);
				fclose($fp);
			}
			return $data;
		}


}
