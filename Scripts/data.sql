/*Users Insert*/
INSERT INTO `tym`.`users`
(`name`, `email`, `password`, `created_at`, `updated_at`)
VALUES
('Enrique Castro', 'enrique@gmail.com', '$2y$10$SAQvSs5RUI6NiGlMCAXiyOSWaTv5pGt7P4s.OptA1rBy59jGgOLQG', now(), now());

INSERT INTO `tym`.`users`
(`name`, `email`, `password`, `created_at`, `updated_at`)
VALUES
('Andrea Rojas', 'andrea@gmail.com', '$2y$10$SAQvSs5RUI6NiGlMCAXiyOSWaTv5pGt7P4s.OptA1rBy59jGgOLQG', now(), now());

INSERT INTO `tym`.`users`
(`name`, `email`, `password`, `created_at`, `updated_at`)
VALUES
('Yara Araya', 'yara@gmail.com', '$2y$10$SAQvSs5RUI6NiGlMCAXiyOSWaTv5pGt7P4s.OptA1rBy59jGgOLQG', now(), now());

/*Monedas Insert*/
INSERT INTO `tym`.`coins`
(`name`, `symbol`, `description`, `created_at`, `updated_at`)
VALUES
('USD', '$', 'Dólar Estadounidense', now(), now());

INSERT INTO `tym`.`coins`
(`name`, `symbol`, `description`, `created_at`, `updated_at`)
VALUES
('CRC', '₡', 'Colón Costarricense', now(), now());

/*Users_coins Insert*/
INSERT INTO `tym`.`users_coins`
(`rate`, `user_id`, `coin_id`, `local`, `created_at`, `updated_at`)
VALUES
(1, 1, 2, true, now(), now());

INSERT INTO `tym`.`users_coins`
(`rate`, `user_id`, `coin_id`, `local`, `created_at`, `updated_at`)
VALUES
(613, 1, 1, false, now(), now());

INSERT INTO `tym`.`users_coins`
(`rate`, `user_id`, `coin_id`, `local`, `created_at`, `updated_at`)
VALUES
(1, 2, 2, true, now(), now());

INSERT INTO `tym`.`users_coins`
(`rate`, `user_id`, `coin_id`, `local`, `created_at`, `updated_at`)
VALUES
(613, 2, 1, false, now(), now());

/*Categorías Insert*/

INSERT INTO `tym`.`categorias`
(`user_id`, `moneda_id`, `tipo`, `descripcion`, `presupuesto`, `rebajo`, `created_at`, `updated_at`)
VALUES
(1, 2, 'Gasto', 'Vivienda', 112000, 110000, now(), now());

INSERT INTO `tym`.`categorias`
(`user_id`, `categoria_id`, `moneda_id`, `tipo`, `descripcion`, `presupuesto`, `rebajo`, `created_at`, `updated_at`)
VALUES
(1, 1, 2, 'Gasto', 'Comida', 100000, 30000, now(), now());

INSERT INTO `tym`.`categorias`
(`user_id`, `moneda_id`, `tipo`, `descripcion`, `presupuesto`, `rebajo`, `created_at`, `updated_at`)
VALUES
(1, 1, 'Gasto', 'Licencia', 40, 40, now(), now());

INSERT INTO `tym`.`categorias`
(`user_id`, `moneda_id`, `tipo`, `descripcion`, `presupuesto`, `rebajo`, `created_at`, `updated_at`)
VALUES
(2, 2, 'Gasto', 'Viaje', 100000, 100000, now(), now());

INSERT INTO `tym`.`categorias`
(`user_id`, `moneda_id`, `tipo`, `descripcion`, `presupuesto`, `rebajo`, `created_at`, `updated_at`)
VALUES
(2, 2, 'Gasto', 'Medico', 60000, 60000, now(), now());

/*Cuentas insert*/
INSERT INTO `tym`.`cuentas`
(`user_id`, `monedas_id`, `nombre`, `descripcion`, `saldo`, `created_at`, `updated_at`)
VALUES
(1, 1, 'Banco BCR', 'Cuenta ahorros - colones', 100000, now(), now());

INSERT INTO `tym`.`cuentas`
(`user_id`, `monedas_id`, `nombre`, `descripcion`, `saldo`, `created_at`, `updated_at`)
VALUES
(2, 2, 'Banco Scotiabank', 'Cuenta ahorros - dólares', 100, now(), now());

/*Transacciones Insert*/
INSERT INTO `tym`.`transactions`
(`user_id`, `cuenta_id`, `categoria_id`, `tipo`, `monto`, `detalle`, `created_at`, `updated_at`)
VALUES
(1, 1, 1, 'Ingreso', 10000, 'Ingreso de dinero', '2020-11-22 22:12:09', '2020-11-22 22:12:09');

INSERT INTO `tym`.`transactions`
(`user_id`, `cuenta_id`, `categoria_id`, `tipo`, `monto`, `detalle`, `created_at`, `updated_at`)
VALUES
(1, 1, 1, 'Gasto', 20000, 'Limpieza', '2021-03-22 22:12:09', '2021-03-22 22:12:09');

INSERT INTO `tym`.`transactions`
(`user_id`, `cuenta_id`, `categoria_id`, `tipo`, `monto`, `detalle`, `created_at`, `updated_at`)
VALUES
(1, 1, 2, 'Gasto', 70000, 'Canasta', '2021-03-22 22:12:09', '2021-03-22 22:12:09');