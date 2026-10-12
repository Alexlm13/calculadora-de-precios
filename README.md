# Calculadora de precios

Pequeña calculadora de precios implementada en **tres lenguajes** (Python, JavaScript y PHP). El código está documentado directamente con comentarios, y la documentación técnica se genera automáticamente con la herramienta propia de cada lenguaje, en varios formatos de salida.

## ¿Qué hace?

Cada versión ofrece las mismas tres operaciones:

| Operación | Python | JavaScript | PHP |
|-----------|--------|------------|-----|
| Calcular el precio con IVA | `calcular_precio_con_iva` | `calcularPrecioConIva` | `calcularPrecioConIva` |
| Aplicar un descuento | `aplicar_descuento` | `aplicarDescuento` | `aplicarDescuento` |
| Calcular el precio final | `calcular_precio_final` | `calcularPrecioFinal` | `calcularPrecioFinal` |

- El **IVA** por defecto es del 21 %.
- El **descuento** se aplica sobre el precio base y el **IVA** sobre el precio ya descontado.
- Los resultados se redondean a dos decimales.
- Si algún dato no es válido (precio o IVA negativos, descuento fuera de 0-100), se produce un error con un mensaje claro.

Ejemplo: un producto de 100 € con un 10 % de descuento y un 21 % de IVA cuesta `100 × 0,90 × 1,21 = 108,90 €`.

## Estructura del proyecto

```
calculadora-precios/
├── README.md
├── .gitignore
├── python/
│   ├── calculadora.py
│   └── sphinx/              → configuración de Sphinx
├── javascript/
│   ├── calculadora.js
│   ├── jsdoc.json           → configuración de JSDoc
│   └── package.json
├── php/
│   └── calculadora.php
└── docs/
    ├── index.md             → índice de la documentación
    ├── python/              → salida de Sphinx (html, epub, text, man)
    ├── javascript/          → salida de JSDoc (html y JSON)
    └── php/                 → salida de phpDocumentor (html, clean, xml)
```

## Uso

Los tres programas se usan igual: el precio y, de forma opcional, el descuento y el IVA. Con 100 €, un 10 % de descuento y un 21 % de IVA, todos muestran `Precio final: 108.90 €`.

| Lenguaje | Comando |
|----------|---------|
| Python 3 | `python python/calculadora.py 100 --descuento 10 --iva 21` |
| JavaScript (Node.js) | `node javascript/calculadora.js 100 --descuento 10 --iva 21` |
| PHP 8.1 o superior | `php php/calculadora.php 100 --descuento 10 --iva 21` |

## Documentación técnica

Los comentarios del código siguen la convención de cada lenguaje: docstrings según PEP 257 en Python, JSDoc en JavaScript y docblocks PHPDoc en PHP. Con ellos se genera la documentación en la carpeta `docs/`:

| Lenguaje | Herramienta | Formatos de salida |
|----------|-------------|--------------------|
| Python | [Sphinx](https://www.sphinx-doc.org) | HTML, ePub, texto plano y página de manual |
| JavaScript | [JSDoc](https://jsdoc.app) | HTML y JSON |
| PHP | [phpDocumentor](https://www.phpdoc.org) | HTML (plantillas `default` y `clean`) y XML |

### Python con Sphinx

```
python -m pip install sphinx
python -m sphinx -b html -d docs/_build/doctrees python/sphinx docs/python/html
python -m sphinx -b epub -d docs/_build/doctrees python/sphinx docs/python/epub
python -m sphinx -b text -d docs/_build/doctrees python/sphinx docs/python/text
python -m sphinx -b man  -d docs/_build/doctrees python/sphinx docs/python/man
```

### JavaScript con JSDoc

```
cd javascript
npm install --save-dev jsdoc
npm run docs
npx jsdoc -c jsdoc.json -X > ../docs/javascript/doclets.json
cd ..
```

### PHP con phpDocumentor

Descarga `phpDocumentor.phar` desde [su página de releases](https://github.com/phpDocumentor/phpDocumentor/releases) y ejecútalo desde una carpeta cuya ruta **no contenga caracteres especiales** (º, acentos, ñ...), porque phpDocumentor no los soporta:

```
php phpDocumentor.phar -f calculadora.php -t html
php phpDocumentor.phar -f calculadora.php -t clean --template=clean
php phpDocumentor.phar -f calculadora.php -t xml --template=xml
```

Después se copian las carpetas resultantes a `docs/php/`.

## Control de versiones

El proyecto se ha desarrollado con Git y GitHub. El primer commit contiene el `README.md` y el `.gitignore`, y cada lenguaje se desarrolló en su propia rama, que se fusionó en `main` mediante un Pull Request:

| Rama | Contenido |
|------|-----------|
| `calculadoraPython` | Calculadora en Python y documentación con Sphinx |
| `calculadoraJavaScript` | Calculadora en JavaScript y documentación con JSDoc |
| `calculadoraPHP` | Calculadora en PHP y documentación con phpDocumentor |

Los archivos temporales (`__pycache__/`, `*.pyc`, `docs/_build/`, `node_modules/`, la caché de phpDocumentor) están excluidos mediante `.gitignore`.
