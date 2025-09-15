<?php

namespace App\Helper;

use Illuminate\Http\Request;

class Helper
{
	public static function relativeTime($time1, $time2, $shortTime = false)
	{
		if ($time1 < $time2)
		{
			return false;
		}

		$dtF = new \DateTime('@'.$time1);
		$dtT = new \DateTime("@".$time2);

		$time = '';

		$timeLimitLeftYears = $dtF->diff($dtT)->format('%y');
		if ($timeLimitLeftYears == 1)
		{
			if ($shortTime)
				return '1 year';

			$time = '1 year';
		}
		else if ($timeLimitLeftYears >= 1)
		{
			if ($shortTime)
				return $timeLimitLeftYears.' year';
			
			$time = $timeLimitLeftYears.' years';
		}

		$timeLimitLeftMonths = $dtF->diff($dtT)->format('%m');
		if ($timeLimitLeftMonths == 1)
		{
			if ($shortTime)
				return '1 month';

			if ($time != '')
				return $time.' and 1 month ago';
			else
			{
				$time = '1 month';
			}
		}
		else if ($timeLimitLeftMonths >= 1)
		{
			if ($shortTime)
				return $timeLimitLeftMonths.' month';

			if ($time != '')
				return $time.' and '.$timeLimitLeftMonths.' months ago';
			else
			{
				$time = $timeLimitLeftMonths.' months';
			}
		}

		$timeLimitLeftDays = $dtF->diff($dtT)->format('%d');
		if ($timeLimitLeftDays == 1)
		{
			if ($shortTime)
				return '1 day';

			if ($time != '')
				return $time.' and 1 day ago';
			else
			{
				$time = '1 day';
			}
		}
		else if ($timeLimitLeftDays >= 1)
		{
			if ($shortTime)
				return $timeLimitLeftDays.' day';

			if ($time != '')
				return $time.' and '.$timeLimitLeftDays.' days ago';
			else
			{
				$time = $timeLimitLeftDays.' days';
			}
		}

		$timeLimitLeftHours = $dtF->diff($dtT)->format('%h');
		if ($timeLimitLeftHours == 1)
		{
			if ($shortTime)
				return '1 hour';

			if ($time != '')
				return $time.' and 1 hour ago';
			else
			{
				$time = '1 hour';
			}
		}
		else if ($timeLimitLeftHours >= 1)
		{
			if ($shortTime)
				return $timeLimitLeftHours.' hour';

			if ($time != '')
				return $time.' and '.$timeLimitLeftHours.' hours ago';
			else
			{
				$time = $timeLimitLeftHours.' hours';
			}
		}

		$timeLimitLeftMinutes = $dtF->diff($dtT)->format('%i');
		if ($timeLimitLeftMinutes == 1)
		{
			if ($shortTime)
				return '1 min';

			if ($time != '')
				return $time.' and 1 minute ago';
			else
			{
				$time = '1 minute';
			}
		}
		else if ($timeLimitLeftMinutes >= 1)
		{
			if ($shortTime)
				return $timeLimitLeftMinutes.' min';

			if ($time != '')
				return $time.' and '.$timeLimitLeftMinutes.' minutes ago';
			else
			{
				$time = $timeLimitLeftMinutes.' minutes';
			}
		}

		if ($time != '')
			return $time.' ago';

		//$timeLimitLeftSeconds = $dtF->diff($dtT)->format('%s');
		// if ($timeLimitLeftSeconds == 1)
		// 	$times[] = '1 second';
		// else if ($timeLimitLeftSeconds >= 1)
		// 	$times[] = $timeLimitLeftSeconds.' seconds';

		return 'Just now';
	}
}

?>