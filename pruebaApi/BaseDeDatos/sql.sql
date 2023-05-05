CREATE TABLE "infoUsuario" (
	"id"	INTEGER NOT NULL UNIQUE,
	"nombre"	TEXT NOT NULL,
	"dni"	TEXT NOT NULL UNIQUE,
	"email"	TEXT NOT NULL,
	"capital_solicitado"	INTEGER NOT NULL,
	"cuota_mensual"	INTEGER,
	"importe_total"	INTEGER,
	PRIMARY KEY("id")
);