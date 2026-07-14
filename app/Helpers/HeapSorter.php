<?php

namespace App\Helpers;

class HeapSorter
{
    /**
     * Ordena una colección o array de productos por precio usando Heapsort.
     */
    public static function sort(array &$array): void
    {
        $n = count($array);

        for ($i = floor($n / 2) - 1; $i >= 0; $i--) {
            self::heapify($array, $n, $i);
        }

        for ($i = $n - 1; $i > 0; $i--) {
            $temp = $array[0];
            $array[0] = $array[$i];
            $array[$i] = $temp;

            self::heapify($array, $i, 0);
        }
    }

    private static function heapify(array &$array, int $n, int $i): void
    {
        $largest = $i;       
        $left = 2 * $i + 1;  
        $right = 2 * $i + 2; 

        if ($left < $n && $array[$left]->precio > $array[$largest]->precio) {
            $largest = $left;
        }

        if ($right < $n && $array[$right]->precio > $array[$largest]->precio) {
            $largest = $right;
        }

        if ($largest != $i) {
            $swap = $array[$i];
            $array[$i] = $array[$largest];
            $array[$largest] = $swap;

            self::heapify($array, $n, $largest);
        }
    }
}