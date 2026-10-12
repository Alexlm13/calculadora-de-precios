"""Configuración de Sphinx para la documentación de la calculadora de precios."""

import os
import sys

# Carpeta python/, donde está calculadora.py, para que autodoc pueda importarlo.
sys.path.insert(0, os.path.abspath(".."))

project = "Calculadora de precios"
author = "Alumno"
release = "1.0"
language = "es"

extensions = [
    "sphinx.ext.autodoc",   # genera la documentación a partir de los docstrings
    "sphinx.ext.napoleon",  # entiende docstrings con secciones Args, Returns...
    "sphinx.ext.viewcode",  # añade enlaces al código fuente
]

html_theme = "alabaster"
exclude_patterns = ["_build"]
