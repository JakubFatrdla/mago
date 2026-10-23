<?php

function first($rows)
{
	return $rows->first()
		->where('a', 'b', 'c', 'd');
}
