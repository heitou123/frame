<?php


class Model{
	public function index(){
		echo get_called_class ();
	}
}
//(new Model)->index ();

class Article extends Model
{

}
(new Article())->index ();