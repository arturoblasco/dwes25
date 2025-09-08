--
-- Base de datos: `lol`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campeon`
--

CREATE TABLE `campeon` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `dificultad` enum('Baja','Media','Alta') NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `campeon`
--

INSERT INTO `campeon` (`id`, `nombre`, `rol`, `dificultad`, `descripcion`) VALUES
(1, 'Aatrox', 'Luchador', 'Alta', 'Aatrox, la Espada Darkin, es un coloso que inflige daño físico y se cura con sus ataques.'),
(2, 'Oahri', 'Mago', 'Media', 'Oahri, la Vastaya de Nueve Colas, es una maga que manipula las emociones y consume la esencia de sus presas.'),
(3, 'Bukali', 'Asesino', 'Alta', 'Bukali, la Asesina Furtiva, es una asesina que actúa en solitario para defender Jonia.'),
(4, 'Ulistar', 'Tanque', 'Media', 'Ulistar, el Minotauro, es un tanque que aturde y desplaza a sus enemigos mientras se cura a sí mismo y a sus aliados.'),
(5, 'Zemumu', 'Tanque', 'Baja', 'Zemumu, la Momia Triste, es un tanque que inflige daño en área y aplica efectos de control de masas.'),
(6, 'Nivia', 'Mago', 'Alta', 'Nivia, la Criofénix, es una maga que controla el hielo para dañar y ralentizar a sus enemigos.'),
(7, 'Annie', 'Mago', 'Baja', 'Annie, la Hija de la Oscuridad, es una maga que lanza hechizos de fuego y puede invocar a su oso Tibbers.'),
(8, 'Orphelios', 'Tirador', 'Alta', 'OrAphelios, el Arma de los Adeptos, es un tirador con un arsenal de armas que se adaptan a diferentes situaciones.'),
(9, 'Mushe', 'Tirador', 'Media', 'Mushe, la Arquera de Hielo, es una tiradora que ralentiza a sus enemigos con flechas de hielo y puede lanzar una flecha de cristal encantada.'),
(10, 'Aurelion Sol', 'Mago', 'Alta', 'Aurelion Sol, el Forjador de Estrellas, es un mago que crea y lanza estrellas para dañar a sus enemigos.');

--
-- Indices de la tabla `campeon`
--
ALTER TABLE `campeon`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de la tabla `campeon`
--
ALTER TABLE `campeon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;


