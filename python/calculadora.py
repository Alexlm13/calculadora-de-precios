"""Calculadora de precios.

Este módulo permite calcular el precio de un producto con IVA, aplicar un
descuento y obtener el precio final aplicando ambos. El descuento se aplica
siempre sobre el precio base y el IVA sobre el precio ya descontado.

Uso desde la línea de comandos::

    python calculadora.py 100 --descuento 10 --iva 21

Funciones principales:

* :func:`calcular_precio_con_iva`
* :func:`aplicar_descuento`
* :func:`calcular_precio_final`
"""

import argparse
import sys

IVA_GENERAL = 21.0
"""Porcentaje de IVA general que se aplica por defecto (21 %)."""


def _validar_no_negativo(valor, nombre):
    """Comprueba que un valor numérico no sea negativo.

    Args:
        valor (float): Valor a comprobar.
        nombre (str): Nombre del parámetro, usado en el mensaje de error.

    Raises:
        ValueError: Si ``valor`` es menor que 0.
    """
    if valor < 0:
        raise ValueError(f"{nombre} no puede ser negativo: {valor}")


def calcular_precio_con_iva(precio, iva=IVA_GENERAL):
    """Calcula el precio de un producto incluyendo el IVA.

    Args:
        precio (float): Precio base sin IVA. Debe ser mayor o igual que 0.
        iva (float): Porcentaje de IVA a aplicar. Por defecto 21.

    Returns:
        float: Precio con IVA, redondeado a dos decimales.

    Raises:
        ValueError: Si el precio o el IVA son negativos.

    Example:
        >>> calcular_precio_con_iva(100)
        121.0
        >>> calcular_precio_con_iva(50, iva=10)
        55.0
    """
    _validar_no_negativo(precio, "El precio")
    _validar_no_negativo(iva, "El IVA")
    return round(precio * (1 + iva / 100), 2)


def aplicar_descuento(precio, descuento):
    """Aplica un descuento porcentual a un precio.

    Args:
        precio (float): Precio original. Debe ser mayor o igual que 0.
        descuento (float): Porcentaje de descuento, entre 0 y 100.

    Returns:
        float: Precio tras el descuento, redondeado a dos decimales.

    Raises:
        ValueError: Si el precio es negativo o el descuento no está entre
            0 y 100.

    Example:
        >>> aplicar_descuento(200, 25)
        150.0
        >>> aplicar_descuento(80, 0)
        80.0
    """
    _validar_no_negativo(precio, "El precio")
    if not 0 <= descuento <= 100:
        raise ValueError(f"El descuento debe estar entre 0 y 100: {descuento}")
    return round(precio * (1 - descuento / 100), 2)


def calcular_precio_final(precio, descuento=0.0, iva=IVA_GENERAL):
    """Calcula el precio final aplicando primero el descuento y luego el IVA.

    Args:
        precio (float): Precio base sin IVA. Debe ser mayor o igual que 0.
        descuento (float): Porcentaje de descuento, entre 0 y 100. Por
            defecto 0 (sin descuento).
        iva (float): Porcentaje de IVA a aplicar. Por defecto 21.

    Returns:
        float: Precio final, redondeado a dos decimales.

    Raises:
        ValueError: Si algún parámetro está fuera de su rango válido.

    Example:
        >>> calcular_precio_final(100, descuento=10, iva=21)
        108.9
        >>> calcular_precio_final(100)
        121.0
    """
    precio_descontado = aplicar_descuento(precio, descuento)
    return calcular_precio_con_iva(precio_descontado, iva)


def main(argumentos=None):
    """Ejecuta la calculadora desde la línea de comandos.

    Args:
        argumentos (list[str] | None): Lista de argumentos. Si es ``None``
            se leen de ``sys.argv``.

    Returns:
        int: ``0`` si todo ha ido bien y ``1`` si algún dato no es válido.
    """
    parser = argparse.ArgumentParser(
        description="Calculadora de precios con descuento e IVA."
    )
    parser.add_argument("precio", type=float, help="Precio base sin IVA")
    parser.add_argument(
        "--descuento", type=float, default=0.0,
        help="Porcentaje de descuento, entre 0 y 100 (por defecto 0)",
    )
    parser.add_argument(
        "--iva", type=float, default=IVA_GENERAL,
        help="Porcentaje de IVA (por defecto 21)",
    )
    args = parser.parse_args(argumentos)

    try:
        con_descuento = aplicar_descuento(args.precio, args.descuento)
        final = calcular_precio_final(args.precio, args.descuento, args.iva)
    except ValueError as error:
        print(f"Error: {error}")
        return 1

    print(f"Precio base:   {args.precio:.2f} €")
    print(f"Con descuento: {con_descuento:.2f} € (-{args.descuento:g} %)")
    print(f"Precio final:  {final:.2f} € (IVA {args.iva:g} %)")
    return 0


if __name__ == "__main__":
    sys.exit(main())
