import java.sql.*;

class Database {
    public static void main(String args[]) {
        try {
            Class.forName("com.mysql.jdbc.Driver");
            Connection con = DriverManager.getConnection(
                    "jdbc:mysql://localhost/hms", "root", "");

            Statement stmt = con.createStatement();

            /*stmt.executeUpdate("DROP TABLE IF EXISTS doctors;");
            stmt.executeUpdate("DROP TABLE IF EXISTS patients;");
            stmt.executeUpdate("DROP TABLE IF EXISTS laboratorians;");
            stmt.executeUpdate("DROP TABLE IF EXISTS pharmacists;");
            stmt.executeUpdate("DROP TABLE IF EXISTS persons;");*/
            stmt.executeUpdate("DROP TABLE *");

            String persons = "CREATE TABLE persons" +
                    "(person_id CHAR(11) PRIMARY KEY, " +
                    " first_name VARCHAR(20) NOT NULL, " +
                    " last_name VARCHAR(20) NOT NULL, " +
                    " sex VARCHAR(20) NOT NULL, " +
                    " phone VARCHAR(50) NOT NULL, " +
                    " email VARCHAR(50) NOT NULL, " +
                    " password VARCHAR(50) NOT NULL) " +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(persons);

            String patients = "CREATE TABLE patients" +
                    "(person_id CHAR(11) PRIMARY KEY, " +
                    " birth_date date, " +
                    " weight NUMERIC(3,2), " +
                    " height NUMERIC(3,2), " +
                    " blood_type VARCHAR(10), " +
                    " FOREIGN KEY (person_id) references persons(person_id)) " +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(patients);

            String doctors = "CREATE TABLE doctors" +
                    "(person_id CHAR(11) PRIMARY KEY, " +
                    " title VARCHAR(20)," +
                    " FOREIGN KEY (person_id) references persons(person_id)) " +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(doctors);

            String laboratorians = "CREATE TABLE laboratorians" +
                    "(person_id CHAR(11) PRIMARY KEY," +
                    " FOREIGN KEY (person_id) references persons(person_id)) " +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(laboratorians);

            String pharmacists = "CREATE TABLE pharmacists" +
                    "(person_id CHAR(11) PRIMARY KEY," +
                    " FOREIGN KEY (person_id) references persons(person_id)) " +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(pharmacists);

            String appointment = "CREATE TABLE examination(\n" +
                    "\texam_id\t\tCHAR(11) PRIMARY KEY\n" +
                    "\tdate\t\ttimestamp NOT NULL,\n";
            stmt.executeUpdate(appointment);

            String symptoms = "CREATE TABLE symptoms(\n" +
                    "\tname\t\tVARCHAR(20) PRIMARY KEY\n";
            stmt.executeUpdate(symptoms);

            String schedule = "CREATE TABLE schedule(\n" +
                    "\tdate\t\ttimestamp NOT NULL,\n" +
                    "\toccupation_type\t\tVARCHAR(20) NOT NULL\n";
            stmt.executeUpdate(schedule);

            String department = "CREATE TABLE department(\n" +
                    "\tdepartment_name\t\tVARCHAR(20) PRIMARY KEY\n";
            stmt.executeUpdate(department);

            String drugs = "CREATE TABLE drugs(\n" +
                    "\tdrug_id\t\tCHAR(11) PRIMARY KEY,\n" +
                    "\tname\t\tVARCHAR(20)\n";
            stmt.executeUpdate(drugs);

            String prescriptions = "CREATE TABLE prescriptions(\n" +
                    "\tprescription_id\t\tCHAR(11) PRIMARY KEY,\n";
            stmt.executeUpdate(prescriptions);

            String components = "CREATE TABLE components(\n" +
                    "\tname\t\tVARCHAR(20) PRIMARY KEY,\n" +
                    "\tinterval_high\t\t NUMERIC(3,2),\n" +
                    "\tinterval_low\t\t NUMERIC(3,2),\n";
            stmt.executeUpdate(components);

            String tests = "CREATE TABLE tests(\n" +
                    "\ttest_id\t\tCHAR(11) PRIMARY KEY,\n" +
                    "\tname\t\tVARCHAR(20)\n";
            stmt.executeUpdate(tests);

            String results = "CREATE TABLE results(\n" +
                    "\tresult_id\t\tCHAR(11) PRIMARY KEY,\n" +
                    "\tlaboratorian_comment\t\tVARCHAR(300)\n" +
                    "\tdate\t\ttimestamp NOT NULL,\n";
            stmt.executeUpdate(results);

            String diagnosis = "CREATE TABLE diagnosis(\n" +
                    "\tdiagnosis_id\t\tCHAR(11) PRIMARY KEY,\n" +
                    "\tcomment\t\tVARCHAR(300)\n";
            stmt.executeUpdate(diagnosis);

            String diseases = "CREATE TABLE diseases(\n" +
                    "\tdiseases_id\t\tCHAR(11) PRIMARY KEY,\n" +
                    "\tname\t\tVARCHAR(20)\n";
            stmt.executeUpdate(diseases);

            String symptoms_of = "CREATE TABLE symptoms_of(\n" +
                    "\texam_id\t\tCHAR(11),\n" +
                    "\tname\t\tVARCHAR(20),\n" +
                    "PRIMARY KEY (exam_id, name),\n" +
                    "FOREIGN KEY (exam_id) references appointment(exam_id),\n" +
                    "FOREIGN KEY (name) references symptoms(name)ENGINE=InnoDB\n";
            stmt.executeUpdate(symptoms_of);

            String appointment_of = "CREATE TABLE appointment_of(\n" +
                    "\texam_id\t\tCHAR(11),\n" +
                    "PRIMARY KEY (exam_id),\n" +
                    "FOREIGN KEY (exam_id) references appointment(exam_id)ENGINE=InnoDB\n";
            stmt.executeUpdate(appointment_of);

            String processed_by = "CREATE TABLE processed_by(\n" +
                    "\tprescription_id\t\tCHAR(11),\n" +
                    "PRIMARY KEY (prescription_id),\n" +
                    "FOREIGN KEY (prescription_id) references prescription(prescription_id)ENGINE=InnoDB\n";
            stmt.executeUpdate(processed_by);

            String department_of = "CREATE TABLE department_of(\n" +
                    "\tdepartment_name\t\tCHAR(11),\n" +
                    "PRIMARY KEY (department_name),\n" +
                    "FOREIGN KEY (department_name) references department(department_name)ENGINE=InnoDB\n";
            stmt.executeUpdate(department_of);

            String schedule_of = "CREATE TABLE schedule_of(\n"; //?
            stmt.executeUpdate(schedule_of);

            String alternative_drug = "CREATE TABLE alternative_drug(\n" +
                    "\tdrug_id\t\tCHAR(11),\n" +
                    "\talternative_drug_id\t\tCHAR(11),\n" +
                    "PRIMARY KEY (drug_id, alternative_drug_id),\n" + //?
                    "FOREIGN KEY (alternative_drug_id) references drugs(drug_id),\n" + //?
                    "FOREIGN KEY (drug_id) references drugs(drug_id)ENGINE=InnoDB\n";
            stmt.executeUpdate(alternative_drug);

            String prescribed = "CREATE TABLE prescribed(\n" +
                    "\tprescription_id\t\tCHAR(11),\n" +
                    "PRIMARY KEY (prescription_id),\n" +
                    "FOREIGN KEY (prescription_id) references prescription(prescription_id)ENGINE=InnoDB\n";
            stmt.executeUpdate(prescribed);

            String test_component = "CREATE TABLE test_component(\n" +
                    "\ttest_id\t\tCHAR(11),\n" +
                    "\tname\t\tVARCHAR(20),\n" +
                    "PRIMARY KEY (prescription_id, name),\n" +
                    "FOREIGN KEY (name) references symptoms(name),\n" +
                    "FOREIGN KEY (prescription_id) references prescription(prescription_id)ENGINE=InnoDB\n";
            stmt.executeUpdate(test_component);

            String assigned_tests = "CREATE TABLE assigned_tests(\n" +
                    "\ttest_id\t\tCHAR(11),\n" +
                    "\texam_id\t\tCHAR(11),\n" +
                    "PRIMARY KEY (prescription_id, exam_id),\n" +
                    "FOREIGN KEY (exam_id) references appointment(exam_id),\n" +
                    "FOREIGN KEY (test_id) references tests(test_id)ENGINE=InnoDB\n";
            stmt.executeUpdate(assigned_tests);

            String examination_result = "CREATE TABLE examination_result(\n" +
                    "\tdiagnosis_id\t\tCHAR(11),\n" +
                    "\texam_id\t\tCHAR(11),\n" +
                    "PRIMARY KEY (diagnosis_id, exam_id),\n" +
                    "FOREIGN KEY (exam_id) references appointment(exam_id),\n" +
                    "FOREIGN KEY (diagnosis_id) references diagnosis(diagnosis_id)ENGINE=InnoDB\n";
            stmt.executeUpdate(examination_result);

            String done_by = "CREATE TABLE done_by(\n" +
                    "\tresult_id\t\tCHAR(11),\n" +
                    "PRIMARY KEY (result_id),\n" +
                    "FOREIGN KEY (result_id) references results(result_id)ENGINE=InnoDB\n";
            stmt.executeUpdate(done_by);

            String component_result = "CREATE TABLE component_result(\n" +
                    "\tresult_id\t\tCHAR(11),\n" +
                    "\tname\t\tVARCHAR(20),\n" +
                    "\tstatus\t\tVARCHAR(20),\n" +
                    "\tresult_value\t\tVARCHAR(20),\n" +
                    "PRIMARY KEY (name, result_id),\n" +
                    "FOREIGN KEY (name) references components(name),\n" +
                    "FOREIGN KEY (result_id) references results(result_id)ENGINE=InnoDB\n";
            stmt.executeUpdate(component_result);

            String test_result = "CREATE TABLE test_result(\n" +
                    "\tresult_id\t\tCHAR(11),\n" +
                    "\tprescription_id\t\tCHAR(11),\n" +
                    "PRIMARY KEY (result_id, prescription_id),\n" +
                    "FOREIGN KEY (prescription_id) references prescriptions(prescription_id),\n" +
                    "FOREIGN KEY (result_id) references results(result_id)ENGINE=InnoDB\n";
            stmt.executeUpdate(test_result);

            String diagnosis_result = "CREATE TABLE diagnosis_result(\n" +
                    "\tdiagnosis_id\t\tCHAR(11),\n" +
                    "\tdisease_id\t\tCHAR(11),\n" +
                    "PRIMARY KEY (result_id, disease_id),\n" +
                    "FOREIGN KEY (diagnosis_id) references diagnosis(diagnosis_id),\n" +
                    "FOREIGN KEY (disease_id) references diseases(disease_id)ENGINE=InnoDB\n";
            stmt.executeUpdate(diagnosis_result);

            String chronic_diseases = "CREATE TABLE chronic_diseases(\n" +
                    "\tdisease_id\t\tCHAR(11),\n" +
                    "PRIMARY KEY (disease_id),\n" +
                    "FOREIGN KEY (disease_id) references diseases(disease_id)ENGINE=InnoDB\n";
            stmt.executeUpdate(chronic_diseases);

            stmt.executeUpdate("INSERT INTO persons VALUES ('00000000000', 'Alperen', 'Yalcin', 'Male', '5000000000', 'alperen@email.com', '123456');");
            stmt.executeUpdate("INSERT INTO doctors VALUES ('00000000000', 'aile hekimi' );");


            con.close();
        } catch (Exception e) {
            System.out.println(e);
        }
    }
}