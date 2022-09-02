<?php
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CurrencyTableSeeder extends Seeder {
	/**
	* Run the database seeds.
	*
	* @return void
	*/
	public function run() 
	{
						
				DB::table('currency')->insert([
					'varCountry' => 'Afghanistan', 
					'varCurrency' => 'Afghanis', 
					'varCode' => 'AFN', 
					'varSymbol' => '؋', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Albania', 
					'varCurrency' => 'Leke', 
					'varCode' => 'ALL', 
					'varSymbol' => 'Lek', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'America', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'USD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Argentina', 
					'varCurrency' => 'Pesos', 
					'varCode' => 'ARS', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Aruba', 
					'varCurrency' => 'Guilders', 
					'varCode' => 'AWG', 
					'varSymbol' => 'ƒ', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Australia', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'AUD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Azerbaijan', 
					'varCurrency' => 'New Manats', 
					'varCode' => 'AZN', 
					'varSymbol' => 'ман', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Bahamas', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'BSD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Barbados', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'BBD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Belarus', 
					'varCurrency' => 'Rubles', 
					'varCode' => 'BYR', 
					'varSymbol' => 'p.', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Belgium', 
					'varCurrency' => 'Euro', 
					'varCode' => 'EUR', 
					'varSymbol' => '€', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Beliz', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'BZD', 
					'varSymbol' => 'BZ$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Bermuda', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'BMD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Bolivia', 
					'varCurrency' => 'Bolivianos', 
					'varCode' => 'BOB', 
					'varSymbol' => '$b', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Bosnia and Herzegovina', 
					'varCurrency' => 'Convertible Marka', 
					'varCode' => 'BAM', 
					'varSymbol' => 'KM', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Botswana', 
					'varCurrency' => 'Pula', 
					'varCode' => 'BWP', 
					'varSymbol' => 'P', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Brazil', 
					'varCurrency' => 'Reais', 
					'varCode' => 'BRL', 
					'varSymbol' => 'R$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Britain (United Kingdom)', 
					'varCurrency' => 'Pounds', 
					'varCode' => 'GBP', 
					'varSymbol' => '£', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Brunei Darussalam', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'BND', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Bulgaria', 
					'varCurrency' => 'Leva', 
					'varCode' => 'BGN', 
					'varSymbol' => 'лв', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Cambodia', 
					'varCurrency' => 'Riels', 
					'varCode' => 'KHR', 
					'varSymbol' => '៛', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Canada', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'CAD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Cayman Islands', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'KYD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Chile', 
					'varCurrency' => 'Pesos', 
					'varCode' => 'CLP', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'China', 
					'varCurrency' => 'Yuan Renminbi', 
					'varCode' => 'CNY', 
					'varSymbol' => '¥', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Colombia', 
					'varCurrency' => 'Pesos', 
					'varCode' => 'COP', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Costa Rica', 
					'varCurrency' => 'Colón', 
					'varCode' => 'CRC', 
					'varSymbol' => '₡', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Croatia', 
					'varCurrency' => 'Kuna', 
					'varCode' => 'HRK', 
					'varSymbol' => 'kn', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Cuba', 
					'varCurrency' => 'Pesos', 
					'varCode' => 'CUP', 
					'varSymbol' => '₱', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Cyprus', 
					'varCurrency' => 'Euro', 
					'varCode' => 'EUR', 
					'varSymbol' => '€', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Czech Republic', 
					'varCurrency' => 'Koruny', 
					'varCode' => 'CZK', 
					'varSymbol' => 'Kč', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Denmark', 
					'varCurrency' => 'Kroner', 
					'varCode' => 'DKK', 
					'varSymbol' => 'kr', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Dominican Republic', 
					'varCurrency' => 'Pesos', 
					'varCode' => 'DOP ', 
					'varSymbol' => 'RD$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'East Caribbean', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'XCD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Egypt', 
					'varCurrency' => 'Pounds', 
					'varCode' => 'EGP', 
					'varSymbol' => '£', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'El Salvador', 
					'varCurrency' => 'Colones', 
					'varCode' => 'SVC', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'England (United Kingdom)', 
					'varCurrency' => 'Pounds', 
					'varCode' => 'GBP', 
					'varSymbol' => '£', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Euro', 
					'varCurrency' => 'Euro', 
					'varCode' => 'EUR', 
					'varSymbol' => '€', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Falkland Islands', 
					'varCurrency' => 'Pounds', 
					'varCode' => 'FKP', 
					'varSymbol' => '£', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Fiji', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'FJD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'France', 
					'varCurrency' => 'Euro', 
					'varCode' => 'EUR', 
					'varSymbol' => '€', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Ghana', 
					'varCurrency' => 'Cedis', 
					'varCode' => 'GHC', 
					'varSymbol' => '¢', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Gibraltar', 
					'varCurrency' => 'Pounds', 
					'varCode' => 'GIP', 
					'varSymbol' => '£', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Greece', 
					'varCurrency' => 'Euro', 
					'varCode' => 'EUR', 
					'varSymbol' => '€', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Guatemala', 
					'varCurrency' => 'Quetzales', 
					'varCode' => 'GTQ', 
					'varSymbol' => 'Q', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Guernsey', 
					'varCurrency' => 'Pounds', 
					'varCode' => 'GGP', 
					'varSymbol' => '£', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Guyana', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'GYD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Holland (Netherlands)', 
					'varCurrency' => 'Euro', 
					'varCode' => 'EUR', 
					'varSymbol' => '€', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Honduras', 
					'varCurrency' => 'Lempiras', 
					'varCode' => 'HNL', 
					'varSymbol' => 'L', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Hong Kong', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'HKD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Hungary', 
					'varCurrency' => 'Forint', 
					'varCode' => 'HUF', 
					'varSymbol' => 'Ft', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Iceland', 
					'varCurrency' => 'Kronur', 
					'varCode' => 'ISK', 
					'varSymbol' => 'kr', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'India', 
					'varCurrency' => 'Rupees', 
					'varCode' => 'INR', 
					'varSymbol' => '₹', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Indonesia', 
					'varCurrency' => 'Rupiahs', 
					'varCode' => 'IDR', 
					'varSymbol' => 'Rp', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Iran', 
					'varCurrency' => 'Rials', 
					'varCode' => 'IRR', 
					'varSymbol' => '﷼', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Ireland', 
					'varCurrency' => 'Euro', 
					'varCode' => 'EUR', 
					'varSymbol' => '€', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Isle of Man', 
					'varCurrency' => 'Pounds', 
					'varCode' => 'IMP', 
					'varSymbol' => '£', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Israel', 
					'varCurrency' => 'New Shekels', 
					'varCode' => 'ILS', 
					'varSymbol' => '₪', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Italy', 
					'varCurrency' => 'Euro', 
					'varCode' => 'EUR', 
					'varSymbol' => '€', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Jamaica', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'JMD', 
					'varSymbol' => 'J$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Japan', 
					'varCurrency' => 'Yen', 
					'varCode' => 'JPY', 
					'varSymbol' => '¥', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Jersey', 
					'varCurrency' => 'Pounds', 
					'varCode' => 'JEP', 
					'varSymbol' => '£', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Kazakhstan', 
					'varCurrency' => 'Tenge', 
					'varCode' => 'KZT', 
					'varSymbol' => 'лв', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Korea (North)', 
					'varCurrency' => 'Won', 
					'varCode' => 'KPW', 
					'varSymbol' => '₩', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Korea (South)', 
					'varCurrency' => 'Won', 
					'varCode' => 'KRW', 
					'varSymbol' => '₩', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Kyrgyzstan', 
					'varCurrency' => 'Soms', 
					'varCode' => 'KGS', 
					'varSymbol' => 'лв', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Laos', 
					'varCurrency' => 'Kips', 
					'varCode' => 'LAK', 
					'varSymbol' => '₭', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Latvia', 
					'varCurrency' => 'Lati', 
					'varCode' => 'LVL', 
					'varSymbol' => 'Ls', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Lebanon', 
					'varCurrency' => 'Pounds', 
					'varCode' => 'LBP', 
					'varSymbol' => '£', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Liberia', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'LRD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Liechtenstein', 
					'varCurrency' => 'Switzerland Francs', 
					'varCode' => 'CHF', 
					'varSymbol' => 'CHF', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Lithuania', 
					'varCurrency' => 'Litai', 
					'varCode' => 'LTL', 
					'varSymbol' => 'Lt', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Luxembourg', 
					'varCurrency' => 'Euro', 
					'varCode' => 'EUR', 
					'varSymbol' => '€', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Macedonia', 
					'varCurrency' => 'Denars', 
					'varCode' => 'MKD', 
					'varSymbol' => 'ден', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Malaysia', 
					'varCurrency' => 'Ringgits', 
					'varCode' => 'MYR', 
					'varSymbol' => 'RM', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Malta', 
					'varCurrency' => 'Euro', 
					'varCode' => 'EUR', 
					'varSymbol' => '€', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Mauritius', 
					'varCurrency' => 'Rupees', 
					'varCode' => 'MUR', 
					'varSymbol' => '₨', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Mexico', 
					'varCurrency' => 'Pesos', 
					'varCode' => 'MXN', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Mongolia', 
					'varCurrency' => 'Tugriks', 
					'varCode' => 'MNT', 
					'varSymbol' => '₮', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Mozambique', 
					'varCurrency' => 'Meticais', 
					'varCode' => 'MZN', 
					'varSymbol' => 'MT', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Namibia', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'NAD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Nepal', 
					'varCurrency' => 'Rupees', 
					'varCode' => 'NPR', 
					'varSymbol' => '₨', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Netherlands', 
					'varCurrency' => 'Euro', 
					'varCode' => 'EUR', 
					'varSymbol' => '€', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Netherlands Antilles', 
					'varCurrency' => 'Guilders', 
					'varCode' => 'ANG', 
					'varSymbol' => 'ƒ', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'New Zealand', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'NZD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Nicaragua', 
					'varCurrency' => 'Cordobas', 
					'varCode' => 'NIO', 
					'varSymbol' => 'C$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Nigeria', 
					'varCurrency' => 'Nairas', 
					'varCode' => 'NGN', 
					'varSymbol' => '₦', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'North Korea', 
					'varCurrency' => 'Won', 
					'varCode' => 'KPW', 
					'varSymbol' => '₩', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Norway', 
					'varCurrency' => 'Krone', 
					'varCode' => 'NOK', 
					'varSymbol' => 'kr', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Oman', 
					'varCurrency' => 'Rials', 
					'varCode' => 'OMR', 
					'varSymbol' => '﷼', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Pakistan', 
					'varCurrency' => 'Rupees', 
					'varCode' => 'PKR', 
					'varSymbol' => '₨', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Panama', 
					'varCurrency' => 'Balboa', 
					'varCode' => 'PAB', 
					'varSymbol' => 'B/.', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Paraguay', 
					'varCurrency' => 'Guarani', 
					'varCode' => 'PYG', 
					'varSymbol' => 'Gs', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Peru', 
					'varCurrency' => 'Nuevos Soles', 
					'varCode' => 'PEN', 
					'varSymbol' => 'S/.', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Philippines', 
					'varCurrency' => 'Pesos', 
					'varCode' => 'PHP', 
					'varSymbol' => 'Php', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Poland', 
					'varCurrency' => 'Zlotych', 
					'varCode' => 'PLN', 
					'varSymbol' => 'zł', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Qatar', 
					'varCurrency' => 'Rials', 
					'varCode' => 'QAR', 
					'varSymbol' => '﷼', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Romania', 
					'varCurrency' => 'New Lei', 
					'varCode' => 'RON', 
					'varSymbol' => 'lei', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Russia', 
					'varCurrency' => 'Rubles', 
					'varCode' => 'RUB', 
					'varSymbol' => 'руб', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Saint Helena', 
					'varCurrency' => 'Pounds', 
					'varCode' => 'SHP', 
					'varSymbol' => '£', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Saudi Arabia', 
					'varCurrency' => 'Riyals', 
					'varCode' => 'SAR', 
					'varSymbol' => '﷼', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Serbia', 
					'varCurrency' => 'Dinars', 
					'varCode' => 'RSD', 
					'varSymbol' => 'Дин.', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Seychelles', 
					'varCurrency' => 'Rupees', 
					'varCode' => 'SCR', 
					'varSymbol' => '₨', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Singapore', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'SGD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Slovenia', 
					'varCurrency' => 'Euro', 
					'varCode' => 'EUR', 
					'varSymbol' => '€', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Solomon Islands', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'SBD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Somalia', 
					'varCurrency' => 'Shillings', 
					'varCode' => 'SOS', 
					'varSymbol' => 'S', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'South Africa', 
					'varCurrency' => 'Rand', 
					'varCode' => 'ZAR', 
					'varSymbol' => 'R', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'South Korea', 
					'varCurrency' => 'Won', 
					'varCode' => 'KRW', 
					'varSymbol' => '₩', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Spain', 
					'varCurrency' => 'Euro', 
					'varCode' => 'EUR', 
					'varSymbol' => '€', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Sri Lanka', 
					'varCurrency' => 'Rupees', 
					'varCode' => 'LKR', 
					'varSymbol' => '₨', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Suriname', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'SRD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Sweden', 
					'varCurrency' => 'Kronor', 
					'varCode' => 'SEK', 
					'varSymbol' => 'kr', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Switzerland', 
					'varCurrency' => 'Francs', 
					'varCode' => 'CHF', 
					'varSymbol' => 'CHF', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Syria', 
					'varCurrency' => 'Pounds', 
					'varCode' => 'SYP', 
					'varSymbol' => '£', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Taiwan', 
					'varCurrency' => 'New Dollars', 
					'varCode' => 'TWD', 
					'varSymbol' => 'NT$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Thailand', 
					'varCurrency' => 'Baht', 
					'varCode' => 'THB', 
					'varSymbol' => '฿', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Trinidad and Tobago', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'TTD', 
					'varSymbol' => 'TT$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Turkey', 
					'varCurrency' => 'Lira', 
					'varCode' => 'TRY', 
					'varSymbol' => 'TL', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Turkey', 
					'varCurrency' => 'Liras', 
					'varCode' => 'TRL', 
					'varSymbol' => '£', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Tuvalu', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'TVD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Ukraine', 
					'varCurrency' => 'Hryvnia', 
					'varCode' => 'UAH', 
					'varSymbol' => '₴', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'United Kingdom', 
					'varCurrency' => 'Pounds', 
					'varCode' => 'GBP', 
					'varSymbol' => '£', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'United States of America', 
					'varCurrency' => 'Dollars', 
					'varCode' => 'USD', 
					'varSymbol' => '$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Uruguay', 
					'varCurrency' => 'Pesos', 
					'varCode' => 'UYU', 
					'varSymbol' => '$U', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Uzbekistan', 
					'varCurrency' => 'Sums', 
					'varCode' => 'UZS', 
					'varSymbol' => 'лв', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Vatican City', 
					'varCurrency' => 'Euro', 
					'varCode' => 'EUR', 
					'varSymbol' => '€', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Venezuela', 
					'varCurrency' => 'Bolivares Fuertes', 
					'varCode' => 'VEF', 
					'varSymbol' => 'Bs', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Vietnam', 
					'varCurrency' => 'Dong', 
					'varCode' => 'VND', 
					'varSymbol' => '₫', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Yemen', 
					'varCurrency' => 'Rials', 
					'varCode' => 'YER', 
					'varSymbol' => '﷼', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
				
				DB::table('currency')->insert([
					'varCountry' => 'Zimbabwe', 
					'varCurrency' => 'Zimbabwe Dollars', 
					'varCode' => 'ZWD', 
					'varSymbol' => 'Z$', 
					'created_at'=> Carbon::now(),
					'updated_at'=> Carbon::now()
				]);
							
		
	}
}