<?php

function iframe($atts = [], $content = null, $tag = '')
{
	$post = get_post();
	$output = '';
	$output .= '<div>
                <iframe src="'.$link.'">
                </iframe>
            </div>';
	return $output;

}