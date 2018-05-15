<?php namespace App;
 
 use Illuminate\Database\Eloquent\Model;
 
 
 class Ads extends Model
 {
     
     protected $fillable = ['title', 'content', 'category','view_counter', 'status'];
     
 }
 