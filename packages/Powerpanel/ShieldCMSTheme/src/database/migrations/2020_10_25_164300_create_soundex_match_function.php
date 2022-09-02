<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateSoundexMatchFunction extends Migration {
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up() {
		/*DB::unprepared(
			"CREATE function soundex_match(needle varchar(128), haystack text, splitChar varchar(1))
				RETURNS tinyint(4)
				BEGIN
  				declare spacePos int;
    			declare searchLen int default length(haystack);
			    declare curWord varchar(128) default '';
			    declare tempStr text default haystack;
			    declare tmp text default '';
			    declare soundx1 varchar(64) default soundex(needle);
			    declare soundx2 varchar(64) default '';
			    set spacePos = locate(splitChar, tempStr);
			    while searchLen > 0 do
      		if spacePos = 0 then
        		set tmp = tempStr;
        		select soundex(tmp) into soundx2;
        		if soundx1 = soundx2 then
          		return 1;
        		else
          		return 0;
        		end if;
      		end if;
		      if spacePos != 0 then
    		    set tmp = substr(tempStr, 1, spacePos-1);
        		set soundx2 = soundex(tmp);
        	if soundx1 = soundx2 then
          	return 1;
        	end if;
        	set tempStr = substr(tempStr, spacePos+1);
        	set searchLen = length(tempStr);
      	end if;
	      set spacePos = locate(splitChar, tempStr);
	    end while;
    return 0;
		END;");*/
	}
	/**
  * Reverse the migrations.
  *
  * @return void
  */
	public function down() {
		DB::unprepared('DROP FUNCTION IF EXISTS soundex_match');
	}
}
