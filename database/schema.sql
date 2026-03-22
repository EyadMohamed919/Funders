CREATE TABLE "donation" (
  "donation_id" int NOT NULL AUTO_INCREMENT,
  "donation_amount" double DEFAULT NULL,
  "donation_date" date DEFAULT NULL,
  "user_id" int DEFAULT NULL,
  PRIMARY KEY ("donation_id"),
  KEY "fk_donation_user_id_idx" ("user_id"),
  CONSTRAINT "fk_donation_user_id" FOREIGN KEY ("user_id") REFERENCES "user" ("user_id") ON DELETE CASCADE ON UPDATE CASCADE
);S