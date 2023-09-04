<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class GeneralController extends Controller
{
   
    public function index(Request $request)
    {
        $text = $request->input("q");
        $type = $request->input("o");

        $width       = 800;
        $height      = 500;
        $center_x    = $width / 2;
        $center_y    = $height / 2.5;
        $color1=$type==4?"#fff":"#000";
        $color2=$type==4?"#ddd":"#888";
        $title=$type==4?"UJIAN":($type==3?"KUIS":"E-LEARNING");
        $subs=0;

        
        $img = Image::make(public_path('images/art/b'.$type.'.jpg'));
        $img->resize($width,$height);
        $lines = explode("_",$text);
        $newtext="";
        $sectext="";
        $i=0;
        foreach($lines as $line){
            $i++;
            if($i<3){
                if($i>1){
                    $newtext.=" ".$line;
                }else{
                    $newtext.=$line;
                }
            }

            if($i>2 && $i<4){
                if($i>3){
                    $sectext.=" ".$line;
                }else{
                    $sectext.=$line;
                }
            }
            
        }

        if($sectext!=""){$subs=50;}

        
        $img->text($title,$center_x, $center_y-$subs,function($font)use($color1){
            $font->file(public_path("fonts/Poppins-Bold.ttf"));
            $font->size(80);
            $font->color($color1);
            $font->align("center");
            $font->valign("top");
        });

        $img->text($newtext,$center_x, $center_y+120-$subs,function($font)use($color2){
            $font->file(public_path("fonts/Poppins-Bold.ttf"));
            $font->size(50);
            $font->color($color2);
            $font->align("center");
            $font->valign("top");
        });

        if($sectext!=""){
            $img->text($sectext,$center_x, $center_y+200-$subs,function($font)use($color2){
                $font->file(public_path("fonts/Poppins-Bold.ttf"));
                $font->size(50);
                $font->color($color2);
                $font->align("center");
                $font->valign("top");
            });
        }
                

                
        

        //$img->save(public_path($file_name));

        return $img->response("jpg");
    
    }

 

}