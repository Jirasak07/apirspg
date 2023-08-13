<?php
require_once("MergePdf.class.php");

MergePdf::merge(
	Array(
		"../public/uploads/611420731/2564_1/611420731_registration.pdf",
		"../public/uploads/611420731/2564_1/611420731_certifiledparent.pdf",
		"../public/uploads/611420731/2564_1/611420731_certifiledwitness1.pdf",
		"../public/uploads/611420731/2564_1/611420731_certifiledwitness2.pdf",
	),
	MergePdf::DESTINATION__DISK_INLINE
);