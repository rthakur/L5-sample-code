DROP PROCEDURE IF EXISTS property_data_relocate;

TRUNCATE TABLE property_texts;

DELIMITER $$

CREATE PROCEDURE property_data_relocate()
  BEGIN
    DECLARE cur_finished INTEGER DEFAULT 0;
    DECLARE cur_prop_id INTEGER DEFAULT NULL;
    DECLARE cur_subject_en, cur_subject_fr, cur_subject_de, cur_subject_sv, cur_subject_ar, cur_subject_no,
    cur_subject_es, cur_subject_ma, cur_subject_da, cur_subject_fi, cur_subject_hi, cur_subject_pt, cur_subject_ru,
    cur_subject_ja, cur_subject_uk, cur_subject_it, cur_description_en, cur_description_fr, cur_description_de,
    cur_description_sv, cur_description_ar, cur_description_no, cur_description_es, cur_description_ma,
    cur_description_da, cur_description_fi, cur_description_hi, cur_description_pt, cur_description_ru,
    cur_description_ja, cur_description_uk, cur_description_it TEXT CHARACTER SET utf8
    COLLATE utf8_general_ci DEFAULT NULL;

    DECLARE props_cursor CURSOR FOR (SELECT
                                       id,
                                       subject_en,
                                       subject_fr,
                                       subject_de,
                                       subject_sv,
                                       subject_ar,
                                       subject_no,
                                       subject_es,
                                       subject_ma,
                                       subject_da,
                                       subject_fi,
                                       subject_hi,
                                       subject_pt,
                                       subject_ru,
                                       subject_ja,
                                       subject_uk,
                                       subject_it,
                                       description_en,
                                       description_fr,
                                       description_de,
                                       description_sv,
                                       description_ar,
                                       description_no,
                                       description_es,
                                       description_ma,
                                       description_da,
                                       description_fi,
                                       description_hi,
                                       description_pt,
                                       description_ru,
                                       description_ja,
                                       description_uk,
                                       description_it
                                     FROM property);

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET cur_finished = 1;

    OPEN props_cursor;
    cur: LOOP

      FETCH props_cursor
      INTO cur_prop_id, cur_subject_en, cur_subject_fr, cur_subject_de, cur_subject_sv, cur_subject_ar, cur_subject_no,
        cur_subject_es, cur_subject_ma, cur_subject_da, cur_subject_fi, cur_subject_hi, cur_subject_pt, cur_subject_ru,
        cur_subject_ja, cur_subject_uk, cur_subject_it, cur_description_en, cur_description_fr, cur_description_de,
        cur_description_sv, cur_description_ar, cur_description_no, cur_description_es, cur_description_ma,
        cur_description_da, cur_description_fi, cur_description_hi, cur_description_pt, cur_description_ru,
        cur_description_ja, cur_description_uk, cur_description_it;

      IF cur_finished = 1
      THEN LEAVE cur;
      END IF;

      INSERT INTO property_texts (property_id,
                                  subject_en,
                                  subject_fr,
                                  subject_de,
                                  subject_sv,
                                  subject_ar,
                                  subject_no,
                                  subject_es,
                                  subject_ma,
                                  subject_da,
                                  subject_fi,
                                  subject_hi,
                                  subject_pt,
                                  subject_ru,
                                  subject_ja,
                                  subject_uk,
                                  subject_it,
                                  description_en,
                                  description_fr,
                                  description_de,
                                  description_sv,
                                  description_ar,
                                  description_no,
                                  description_es,
                                  description_ma,
                                  description_da,
                                  description_fi,
                                  description_hi,
                                  description_pt,
                                  description_ru,
                                  description_ja,
                                  description_uk,
                                  description_it) VALUES (
        cur_prop_id, cur_subject_en, cur_subject_fr, cur_subject_de, cur_subject_sv, cur_subject_ar, cur_subject_no,
                     cur_subject_es, cur_subject_ma, cur_subject_da, cur_subject_fi, cur_subject_hi, cur_subject_pt,
                                                                                     cur_subject_ru, cur_subject_ja,
                                                                                     cur_subject_uk, cur_subject_it,
                                                                                     cur_description_en,
                                                                                     cur_description_fr,
                                                                                     cur_description_de,
                                                                                     cur_description_sv,
        cur_description_ar, cur_description_no, cur_description_es, cur_description_ma,
        cur_description_da, cur_description_fi, cur_description_hi, cur_description_pt, cur_description_ru,
        cur_description_ja, cur_description_uk, cur_description_it);

    END LOOP cur;

    CLOSE props_cursor;

  END $$

DELIMITER ;

CALL property_data_relocate();

DROP PROCEDURE IF EXISTS property_data_relocate;


