<?php
$url = "https://www.euronews.ro/topic/stiri-externe";

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 20,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.5993.90 Safari/537.36',
]);

$continut = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Eroare cURL: ' . curl_error($ch);
} else {

    $lista_articole = explode('<article', $continut);
    //echo count($lista_articole); 
    //var_dump($lista_articole);

    for ($i = 1; $i < count($lista_articole); $i++) {
        
        $articol = $lista_articole[$i];

        $titlu_parts = explode('itemprop="headline">', $articol);
        //var_dump($titlu_parts[0]);
        //echo count($titlu_parts);
        if (isset($titlu_parts[0])) {
            $titlu = explode('<h2', $titlu_parts[0]);
            $titlu = explode('"headline">', $titlu[1]);
            $titlu = explode('</h2>', $titlu[1]);
            //var_dump($titlu[1]);
            echo "<strong>Titlu:</strong> " . strip_tags($titlu[0]) . "<br/>";
            //echo "<strong>Data:</strong> " . strip_tags($titlu[1]) . "<br/>";
        }


        $link_parts = explode('href="', $articol);
        if (isset($link_parts[1])) {
            $link = explode('"', $link_parts[1])[0];
            $full_link = (strpos($link, 'http') === 0) ? $link : "https://www.euronews.ro" . $link;
            echo "<strong>Link:</strong> <a href='$full_link'>$full_link</a><br/><br/>";
        }
        
        echo "<hr>"; // Separator visual
    }
}

curl_close($ch);
?>
