<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;

class XssSanitization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $input = $request->all();
        
        $regex[0] = '/\b(and)\b/i';
        $regex[1] = '/\b(or)\b/i';
        $regex[2] = '/\b(drop)\b/i';
        $regex[3] = '/\b(from)\b/i';
        $regex[4] = '/\b(insert)\b/i';
        $regex[5] = '/\b(update)\b/i';
        $regex[6] = '/\b(delete)\b/i';
        $regex[7] = '/\b(sleep)\b/i';
        //$regex[8] = '/\b(--)\b/i';

        if ($request->isMethod('get')) {
            $fullUrl = strtolower(url()->full());
            
            foreach($regex as $listCheck){
                if(preg_match($listCheck,urldecode($fullUrl))==1){
                    exit;
                }
            }
        }
        
        array_walk_recursive($input, function(&$input) use($regex) {
            
            foreach($regex as $listCheck){
                if(preg_match($listCheck,$input)==1){
                    exit;
                }
            }

            $input = strip_tags($input);
            $input = self::remove_special_character($input);
            
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $input)){
                $input = addslashes($input);
            }
        });
        
        $request->merge($input);
        
        return $next($request);
    }

    public static function remove_special_character($string) {
        
        $t = $string;
     
        $specChars = array(
            '!' => '',
            '%' => '',
            '*' => '',
            '/-' => '',
            ';' => '',
            '<' => '',
            '>' => '',
            '?' => '',
            '[' => '',
            '\\' => '',
            ']' => '',
            '^' => '',
            '`' => '',
            '{' => '',
            '}' => '',
            '~' => '',
            '/_' => '-',
            '\\'=>'\\\\',
            "\0"=>'\\0',
            "\n"=>'\\n',
            "\r"=>'\\r',
            "'"=>"\\'",
            '"'=>'\\"',
            "\x1a"=>'\\Z'
        );
     
        foreach ($specChars as $k => $v) {
            $t = str_replace($k, $v, $t);
        }
     
        return $t;
    }
}
