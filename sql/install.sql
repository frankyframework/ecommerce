
insert into `franky` (`php`, `css`, `js`, `jquery`, `resource`, `constante`, `url`, `nombre`, `ajax`, `status`, `editable`, `modulo`) values('paypal/confirmacion.php','','','','[3]','CONFIRMACION_PAYPAL','ecommerce/paypal/confirmacion/','Confirmacion PAYPAL','','1','0','ecommerce');

insert into `franky` (`php`, `css`, `js`, `jquery`, `resource`, `constante`, `url`, `nombre`, `ajax`, `status`, `editable`, `modulo`) values('admin/tiendas/form.php','','[\"validaciones.js\"]','[\"jquery-validate\"]','[3]','FRM_TIENDAS_ECOMMERCE','admin/ecommerce/tiendas/form/','Formulario administracion tiendas','','1','0','ecommerce');



DROP TABLE IF EXISTS `ecommerce_tiendas`;

CREATE TABLE `ecommerce_tiendas` (
  `id` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `telefono` varchar(21) NOT NULL,
  `telefono_otro` varchar(21) DEFAULT NULL,
  `calle` varchar(255) NOT NULL,
  `numero` varchar(5) NOT NULL,
  `numeroi` varchar(5) DEFAULT NULL,
  `cp` varchar(5) NOT NULL,
  `estado` varchar(255) NOT NULL,
  `ciudad` varchar(255) NOT NULL,
  `municipio` varchar(255) NOT NULL,
  `colonia` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
