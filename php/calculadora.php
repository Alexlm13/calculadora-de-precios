<?php

/**
 * Calculadora de precios.
 *
 * Permite calcular el precio de un producto con IVA, aplicar un descuento y
 * obtener el precio final aplicando ambos. El descuento se aplica siempre sobre
 * el precio base y el IVA sobre el precio ya descontado.
 *
 * Uso desde la línea de comandos:
 *
 *     php calculadora.php 100 --descuento 10 --iva 21
 *
 * @package Calculadora
 */

declare(strict_types=1);

/**
 * Porcentaje de IVA general que se aplica por defecto (21 %).
 *
 * @var float
 */
const IVA_GENERAL = 21.0;

/**
 * Comprueba que un valor numérico no sea negativo ni NAN.
 *
 * @param float  $valor  Valor a comprobar.
 * @param string $nombre Nombre del parámetro, usado en el mensaje de error.
 *
 * @return void
 *
 * @throws InvalidArgumentException Si $valor es negativo o no es un número.
 */
function validarNoNegativo(float $valor, string $nombre): void
{
    if (!($valor >= 0)) {
        throw new InvalidArgumentException("$nombre no puede ser negativo: $valor");
    }
}

/**
 * Calcula el precio de un producto incluyendo el IVA.
 *
 * Ejemplo:
 *
 *     calcularPrecioConIva(100);      // 121.0
 *     calcularPrecioConIva(50, 10);   // 55.0
 *
 * @param float $precio Precio base sin IVA. Debe ser mayor o igual que 0.
 * @param float $iva    Porcentaje de IVA a aplicar. Por defecto 21.
 *
 * @return float Precio con IVA, redondeado a dos decimales.
 *
 * @throws InvalidArgumentException Si el precio o el IVA son negativos.
 */
function calcularPrecioConIva(float $precio, float $iva = IVA_GENERAL): float
{
    validarNoNegativo($precio, 'El precio');
    validarNoNegativo($iva, 'El IVA');

    return round($precio * (1 + $iva / 100), 2);
}

/**
 * Aplica un descuento porcentual a un precio.
 *
 * Ejemplo:
 *
 *     aplicarDescuento(200, 25);   // 150.0
 *     aplicarDescuento(80, 0);     // 80.0
 *
 * @param float $precio    Precio original. Debe ser mayor o igual que 0.
 * @param float $descuento Porcentaje de descuento, entre 0 y 100.
 *
 * @return float Precio tras el descuento, redondeado a dos decimales.
 *
 * @throws InvalidArgumentException Si el precio es negativo o el descuento no
 *                                  está entre 0 y 100.
 */
function aplicarDescuento(float $precio, float $descuento): float
{
    validarNoNegativo($precio, 'El precio');
    if (!($descuento >= 0 && $descuento <= 100)) {
        throw new InvalidArgumentException("El descuento debe estar entre 0 y 100: $descuento");
    }

    return round($precio * (1 - $descuento / 100), 2);
}

/**
 * Calcula el precio final aplicando primero el descuento y luego el IVA.
 *
 * Ejemplo:
 *
 *     calcularPrecioFinal(100, 10, 21);   // 108.9
 *     calcularPrecioFinal(100);           // 121.0
 *
 * @param float $precio    Precio base sin IVA. Debe ser mayor o igual que 0.
 * @param float $descuento Porcentaje de descuento, entre 0 y 100. Por defecto 0.
 * @param float $iva       Porcentaje de IVA a aplicar. Por defecto 21.
 *
 * @return float Precio final, redondeado a dos decimales.
 *
 * @throws InvalidArgumentException Si algún parámetro está fuera de su rango válido.
 */
function calcularPrecioFinal(
    float $precio,
    float $descuento = 0.0,
    float $iva = IVA_GENERAL
): float {
    $precioDescontado = aplicarDescuento($precio, $descuento);

    return calcularPrecioConIva($precioDescontado, $iva);
}

/**
 * Ejecuta la calculadora desde la línea de comandos.
 *
 * @param string[] $argumentos Argumentos recibidos: el precio y, de forma
 *                             opcional, `--descuento <n>` y `--iva <n>`.
 *
 * @return int 0 si todo ha ido bien y 1 si algún dato no es válido.
 */
function main(array $argumentos): int
{
    if (!isset($argumentos[0]) || !is_numeric($argumentos[0])) {
        echo "Uso: php calculadora.php <precio> [--descuento <n>] [--iva <n>]\n";
        return 1;
    }

    $precio = (float) $argumentos[0];
    $descuento = 0.0;
    $iva = IVA_GENERAL;

    for ($i = 1; $i < count($argumentos); $i += 2) {
        $valor = $argumentos[$i + 1] ?? '';
        if (!is_numeric($valor)) {
            echo "Error: falta un valor numérico para {$argumentos[$i]}\n";
            return 1;
        }
        if ($argumentos[$i] === '--descuento') {
            $descuento = (float) $valor;
        } elseif ($argumentos[$i] === '--iva') {
            $iva = (float) $valor;
        }
    }

    try {
        $conDescuento = aplicarDescuento($precio, $descuento);
        $final = calcularPrecioFinal($precio, $descuento, $iva);
    } catch (InvalidArgumentException $error) {
        echo 'Error: ' . $error->getMessage() . "\n";
        return 1;
    }

    echo 'Precio base:   ' . number_format($precio, 2, '.', '') . " €\n";
    echo 'Con descuento: ' . number_format($conDescuento, 2, '.', '') . " € (-$descuento %)\n";
    echo 'Precio final:  ' . number_format($final, 2, '.', '') . " € (IVA $iva %)\n";

    return 0;
}

if (PHP_SAPI === 'cli' && isset($argv[0]) && realpath($argv[0]) === __FILE__) {
    exit(main(array_slice($argv, 1)));
}
