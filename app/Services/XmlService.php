<?php

namespace App\Services;

use SimpleXMLElement;
use Exception;

class XmlService
{
    public static function xmlToArray($xmlElement): array
    {
        $array = [];

        foreach ($xmlElement as $key => $value) {
            $itemArray = [];

            foreach ($value->attributes() as $attrName => $attrValue) {
                $itemArray[$attrName] = (string) $attrValue;
            }

            $valueArray = self::xmlToArray($value);

            if (!empty($valueArray)) {
                $itemArray = array_merge($itemArray, $valueArray);
            }

            if (!isset($array[$key])) {
                $array[$key] = $itemArray;
            } else {
                // Si ya existe la clave, conviértelo en un array de items
                if (!is_array($array[$key]) || !isset($array[$key][0])) {
                    $array[$key] = [$array[$key]];
                }
                $array[$key][] = $itemArray;
            }
        }

        if (trim((string) $xmlElement)) {
            $array['#text'] = (string) $xmlElement;
        }

        return $array;
    }

    public static function parsingXML(string $xmlMessage): ?string
    {
        try {
            $xml = new SimpleXMLElement($xmlMessage);
            $jsonArray = self::xmlToArray($xml);

            $json = json_encode($jsonArray, JSON_PRETTY_PRINT);
            file_put_contents(storage_path('app/output.json'), $json); // Mejor práctica en Laravel

            return $json;
        } catch (Exception $e) {
            logger()->error("Error al procesar XML: " . $e->getMessage());
            return null;
        }
    }

    public static function extractXmlAlarmMessage(string &$buffer): array
    {
        if (strpos($buffer, '/SendAlarmData') !== false) {
            $xmlPos = strpos($buffer, '<?xml');
            if ($xmlPos !== false) {
                $xmlContent = substr($buffer, $xmlPos);
                $endPos = strpos($xmlContent, '</config>');

                if ($endPos !== false) {
                    $xmlMessage = substr($xmlContent, 0, $endPos + strlen('</config>'));
                    $buffer = substr($buffer, $endPos + strlen('</config>')); // Limpiar el buffer
                    return [$xmlMessage, $buffer];
                } else {
                    logger()->warning("XML mal formado: no se encontró </config>");
                }
            } else {
                logger()->warning("Encabezado XML no encontrado.");
            }
        }

        return [null, $buffer];
    }

    public static function jsonToArray(string $json): array
    {
        return json_decode($json, true);
    }
}
