<?php
namespace seo_generator;

/**
 * Seo module
 * Create keywords and description
 */
class Seo {

    protected $orderJustMaked = FALSE;
    public $origin_arr;
    public $modif_arr;
    public $min_word_length = 3;
    public $desc_chars = 160;


    /**
     * get_keywords function.
     *
     * @description Create keywrods from text
     * @access public
     * @param mixed $text
     * @param mixed $as_array (default: FALSE)
     * @param mixed $sort (default: FALSE)
     * @return void
     */
    function get_keywords($text, $as_array = FALSE, $sort = FALSE) {
	    $human_array = [];
        $text = strip_tags($text);
        $text = mb_strtolower($text, 'utf-8');
        $this->explode_str_on_words($text);
        $this->count_words();
        $arr = array_slice($this->modif_arr, 0, 30);
        $str = "";

		//sorted by the number of matches
		if($sort)
			arsort($arr);

		$stemmer = new \NXP\Stemmer();

		//simplify array
		foreach ($arr as $key => $val)
            $human_array[] = $stemmer->getWordBase($key);

        if ($as_array == FALSE) {
            $str =  implode(', ', $human_array);
            return trim(mb_substr($str, 0, mb_strlen($str, 'utf-8') - 2));
        }

        return $human_array;
    }

    function get_description($text) {
        $delete = array(';', '"', '  ', '&mdash', '&nbsp;');

        $tags = get_html_translation_table(HTML_ENTITIES);

        foreach ($tags as $k => $v) {
            $text = str_replace($v, '', $text);
        }

        $text = str_replace($delete, '', $text);
        $text = str_replace("\n", ' ', $text);
        $text = str_replace("\r", ' ', $text);

        return trim(mb_substr(strip_tags(stripslashes($text)), 0, 255, 'utf-8'));
    }

    /**
     * 	Explode text on words
     */
    function explode_str_on_words($text) {
        $search = array("'ё'",
            "'<script[^>]*?>.*?</script>'si",
            "'<[\/\!]*?[^<>]*?>'si",
            "'([\r\n])[\s]+'",
            "'&(quot|#34);'i",
            "'&(amp|#38);'i",
            "'&(lt|#60);'i",
            "'&(gt|#62);'i",
            "'&(nbsp|#160);'i",
            "'&(iexcl|#161);'i",
            "'&(cent|#162);'i",
            "'&(pound|#163);'i",
            "'&(copy|#169);'i",
            "'&#(\d+);'e");
        $replace = array("е",
            " ",
            " ",
            "\\1 ",
            "\" ",
            " ",
            " ",
            " ",
            " ",
            chr(161),
            chr(162),
            chr(163),
            chr(169),
            "chr(\\1)");
        $text = preg_replace($search, $replace, $text);
        $del_symbols = array(
        	",", ".", ";", ":", "\"", "#", "\$", "%", "^","«","»",
            "!", "@", "`", "~", "*", "-", "=", "+", "\\",
            "|", "/", ">", "<", "(", ")", "&", "?", "¹", "\t",
            "\r", "\n", "{", "}", "[", "]", "'", "“", "”", "•",
            " как ", " для ", " что ", " или ", " это ", " этих ",
            "всех ", " вас ", " они ", " оно ", " еще ", " когда ",
            " где ", " эта ", " лишь ", " уже ", " вам ", " нет ",
            " если ", " надо ", " все ", " так ", " его ", " чем ",
            " даже ", " мне ", " есть ", " раз ", " два ", "raquo", "laquo",
            "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "mdash"
        );
        $text = str_replace($del_symbols, ' ', $text);
        $text = preg_replace("( +)", " ", $text);
        $this->origin_arr = explode(" ", trim($text));
        return $this->origin_arr;
    }

    /**
     * Count words in text
     */
    function count_words() {
        $tmp_arr = array();
        foreach ($this->origin_arr as $val) {
            if (strlen(utf8_decode($val)) >= $this->min_word_length) {
                $val = mb_strtolower($val, 'utf-8');

                if (array_key_exists($val, $tmp_arr)) {
                    $tmp_arr[$val] ++;
                } else {
                    $tmp_arr[$val] = 1;
                }
            }
        }
//arsort ($tmp_arr);
        $this->modif_arr = $tmp_arr;
    }
}

/* End of file lib_seo.php */
