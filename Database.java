import java.sql.*;

class Database {
    public static void main(String args[]) {
        try {
            Class.forName("com.mysql.jdbc.Driver");
            Connection con = DriverManager.getConnection(
                    "jdbc:mysql://localhost/", "root", "");

            Statement stmt = con.createStatement();


            stmt.executeUpdate("DROP DATABASE IF EXISTS hms");
            stmt.executeUpdate("CREATE DATABASE hms DEFAULT CHARACTER SET utf8");
            stmt.executeUpdate("USE hms");

            /*stmt.executeUpdate("DROP TABLE IF EXISTS doctors;");
            stmt.executeUpdate("DROP TABLE IF EXISTS patients;");
            stmt.executeUpdate("DROP TABLE IF EXISTS laboratorians;");
            stmt.executeUpdate("DROP TABLE IF EXISTS pharmacists;");
            stmt.executeUpdate("DROP TABLE IF EXISTS persons;");*/
            //stmt.executeUpdate("DROP TABLE IF EXISTS *");

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
                    " weight DECIMAL(5,2), " +
                    " height DECIMAL(5,2), " +
                    " blood_type VARCHAR(10), " +
                    " FOREIGN KEY (person_id) references persons (person_id)) " +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(patients);

            String doctors = "CREATE TABLE doctors" +
                    "(person_id CHAR(11) PRIMARY KEY, " +
                    " title VARCHAR(20)," +
                    " FOREIGN KEY (person_id) references persons (person_id)) " +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(doctors);

            String laboratorians = "CREATE TABLE laboratorians" +
                    "(person_id CHAR(11) PRIMARY KEY," +
                    " FOREIGN KEY (person_id) references persons (person_id)) " +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(laboratorians);

            String pharmacists = "CREATE TABLE pharmacists" +
                    "(person_id CHAR(11) PRIMARY KEY," +
                    " FOREIGN KEY (person_id) references persons (person_id)) " +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(pharmacists);

            String appointment = "CREATE TABLE appointment(" +
                    " exam_id CHAR(11) PRIMARY KEY," +
                    " date date NOT NULL)" +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(appointment);

            String symptoms = "CREATE TABLE symptoms(" +
                    " name VARCHAR(20) PRIMARY KEY)"  +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(symptoms);

            String schedule = "CREATE TABLE schedule(" +
                    " schedule_id CHAR(3) PRIMARY KEY," +
                    " date date NOT NULL," +
                    " occupation_type VARCHAR(20) NOT NULL)" +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(schedule);

            String department = "CREATE TABLE department(" +
                    " department_name VARCHAR(20) PRIMARY KEY)" +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(department);

            String drugs = "CREATE TABLE drugs(" +
                    " drug_id CHAR(11) PRIMARY KEY," +
                    " name VARCHAR(20))" +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(drugs);

            String prescriptions = "CREATE TABLE prescriptions(" +
                    " prescription_id CHAR(11) PRIMARY KEY)" +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(prescriptions);

            String components = "CREATE TABLE components(" +
                    " name VARCHAR(20) PRIMARY KEY," +
                    " interval_high NUMERIC(3,2)," +
                    " interval_low NUMERIC(3,2))" +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(components);

            String tests = "CREATE TABLE tests(" +
                    " test_id CHAR(11) PRIMARY KEY," +
                    " name VARCHAR(20))" +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(tests);

            String results = "CREATE TABLE results(" +
                    " result_id CHAR(11) PRIMARY KEY," +
                    " laboratorian_comment VARCHAR(300)," +
                    " date date NOT NULL)" +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(results);

            String diagnosis = "CREATE TABLE diagnosis(" +
                    " diagnosis_id CHAR(11) PRIMARY KEY," +
                    " comment VARCHAR(300))" +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(diagnosis);


            String diseases = "CREATE TABLE diseases(" +
                    " disease_id CHAR(11) PRIMARY KEY," +
                    " name VARCHAR(20))" +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(diseases);

            String symptoms_of = "CREATE TABLE symptoms_of(" +
                    " exam_id CHAR(11)," +
                    " name VARCHAR(20)," +
                    " PRIMARY KEY (exam_id, name)," +
                    " FOREIGN KEY (exam_id) references appointment (exam_id)," +
                    " FOREIGN KEY (name) references symptoms (name))" +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(symptoms_of);

            String appointment_of = "CREATE TABLE appointment_of(" +
                    " exam_id CHAR(11)," +
                    " patient_id CHAR(11), " +
                    " doctor_id CHAR(11), " +
                    " PRIMARY KEY (exam_id, patient_id, doctor_id)," +
                    " FOREIGN KEY (doctor_id) references doctors(person_id)," +
                    " FOREIGN KEY (patient_id) references patients(person_id)," +
                    " FOREIGN KEY (exam_id) references appointment(exam_id))" +
                    " ENGINE=InnoDB;";
            stmt.executeUpdate(appointment_of);

            String processed_by = "CREATE TABLE processed_by(" +
                    " prescription_id CHAR(11)," +
                    " person_id CHAR(11), " +
                    " PRIMARY KEY (prescription_id, person_id)," +
                    " FOREIGN KEY (person_id) references pharmacists(person_id)," +
                    " FOREIGN KEY (prescription_id) references prescriptions(prescription_id))" +
                    " ENGINE=InnoDB";
            stmt.executeUpdate(processed_by);

            String department_of = "CREATE TABLE department_of(" +
                    " person_id CHAR(11), " +
                    " department_name VARCHAR(20)," +
                    " PRIMARY KEY (person_id, department_name)," +
                    " FOREIGN KEY (person_id) references doctors(person_id)," +
                    " FOREIGN KEY (department_name) references department(department_name))ENGINE=InnoDB";
            stmt.executeUpdate(department_of);

            String schedule_of = "CREATE TABLE schedule_of(" +
                    " schedule_id CHAR(3), " +
                    " person_id CHAR(11), " +
                    " PRIMARY KEY(person_id, schedule_id), " +
                    " FOREIGN KEY (person_id) references doctors(person_id)," +
                    " FOREIGN KEY (schedule_id) references schedule(schedule_id))" +
                    " ENGINE=InnoDB";

            stmt.executeUpdate(schedule_of);

            String alternative_drug = "CREATE TABLE alternative_drug(" +
                    " drug_id CHAR(11)," +
                    " alternative_drug_id CHAR(11)," +
                    " PRIMARY KEY (drug_id, alternative_drug_id)," +                    //?
                    " FOREIGN KEY (alternative_drug_id) references drugs(drug_id)," +   //?
                    " FOREIGN KEY (drug_id) references drugs(drug_id))ENGINE=InnoDB";
            stmt.executeUpdate(alternative_drug);

            String prescribed = "CREATE TABLE prescribed(" +
                    " prescription_id CHAR(11)," +
                    " drug_id CHAR(11), "+
                    " PRIMARY KEY (prescription_id, drug_id)," +
                    " FOREIGN KEY (drug_id) references drugs(drug_id), " +
                    " FOREIGN KEY (prescription_id) references prescriptions(prescription_id))ENGINE=InnoDB";
            stmt.executeUpdate(prescribed);

            String test_component = "CREATE TABLE test_component(" +
                    " test_id CHAR(11)," +
                    " name VARCHAR(20)," +
                    " PRIMARY KEY (test_id, name)," +
                    " FOREIGN KEY (name) references components(name)," +
                    " FOREIGN KEY (test_id) references tests(test_id))ENGINE=InnoDB";
            stmt.executeUpdate(test_component);

            String assigned_tests = "CREATE TABLE assigned_tests(" +
                    " test_id CHAR(11)," +
                    " exam_id CHAR(11)," +
                    " PRIMARY KEY (test_id, exam_id)," +
                    " FOREIGN KEY (exam_id) references appointment(exam_id)," +
                    " FOREIGN KEY (test_id) references tests(test_id))ENGINE=InnoDB";
            stmt.executeUpdate(assigned_tests);

            String examination_result = "CREATE TABLE examination_result(" +
                    " diagnosis_id CHAR(11)," +
                    " exam_id CHAR(11)," +
                    " PRIMARY KEY (diagnosis_id, exam_id)," +
                    " FOREIGN KEY (exam_id) references appointment(exam_id)," +
                    " FOREIGN KEY (diagnosis_id) references diagnosis(diagnosis_id))ENGINE=InnoDB";
            stmt.executeUpdate(examination_result);

            String done_by = "CREATE TABLE done_by(" +
                    " result_id CHAR(11)," +
                    " person_id CHAR(11), " +
                    " PRIMARY KEY (result_id, person_id)," +
                    " FOREIGN KEY (person_id) references laboratorians(person_id), " +
                    " FOREIGN KEY (result_id) references results(result_id))ENGINE=InnoDB";
            stmt.executeUpdate(done_by);

            String component_result = "CREATE TABLE component_result(" +
                    " result_id CHAR(11)," +
                    " name VARCHAR(20)," +
                    " status VARCHAR(20)," +
                    " result_value VARCHAR(20)," +
                    " PRIMARY KEY (name, result_id)," +
                    " FOREIGN KEY (name) references components(name)," +
                    " FOREIGN KEY (result_id) references results(result_id))ENGINE=InnoDB";
            stmt.executeUpdate(component_result);

            String test_result = "CREATE TABLE test_result(" +
                    " result_id CHAR(11)," +
                    " exam_id CHAR(11)," +
                    " PRIMARY KEY (result_id, exam_id)," +
                    " FOREIGN KEY (exam_id) references appointment(exam_id)," +
                    " FOREIGN KEY (result_id) references results(result_id))ENGINE=InnoDB";
            stmt.executeUpdate(test_result);

            String diagnosis_result = "CREATE TABLE diagnosis_result(" +
                    " diagnosis_id CHAR(11)," +
                    " disease_id CHAR(11)," +
                    " prescription_id CHAR(11)," +
                    " PRIMARY KEY (diagnosis_id, disease_id, prescription_id)," +
                    " FOREIGN KEY (prescription_id) references prescriptions(prescription_id)," +
                    " FOREIGN KEY (diagnosis_id) references diagnosis(diagnosis_id)," +
                    " FOREIGN KEY (disease_id) references diseases(disease_id))" +
                    " ENGINE=InnoDB";
            stmt.executeUpdate(diagnosis_result);

            String chronic_diseases = "CREATE TABLE chronic_diseases(" +
                    " disease_id CHAR(11)," +
                    " person_id CHAR(11)," +
                    " PRIMARY KEY (disease_id, person_id)," +
                    " FOREIGN KEY (disease_id) references diseases(disease_id)," +
                    " FOREIGN KEY (person_id) references patients(person_id))" +
                    " ENGINE=InnoDB";
            stmt.executeUpdate(chronic_diseases);

            stmt.executeUpdate("INSERT INTO persons VALUES ('10000000000', 'Ali', 'Velioglu', 'Male', '5000000000', 'ali@veli.com', '123456');");
            stmt.executeUpdate("INSERT INTO persons VALUES ('10000000001', 'Ali', 'Delioglu', 'Male', '5000000001', 'ali@deli.com', '123456');");
            stmt.executeUpdate("INSERT INTO persons VALUES ('20000000000', 'Veli', 'Velioglu', 'Male', '5000000002', 'ali@veli.com', '123456');");
            stmt.executeUpdate("INSERT INTO doctors VALUES ('10000000000', 'Professor' );");
            stmt.executeUpdate("INSERT INTO doctors VALUES ('10000000001', 'Specialist' );");
            stmt.executeUpdate("INSERT INTO patients VALUES ('20000000000', '2012-01-01', 62, 156, '0 RH+' );");
            stmt.executeUpdate("INSERT INTO department VALUES ('Internal Medicine');");
            stmt.executeUpdate("INSERT INTO department VALUES ('Cardiology');");
            stmt.executeUpdate("INSERT INTO department VALUES ('Neurology');");
            stmt.executeUpdate("INSERT INTO department_of VALUES ('10000000000', 'Cardiology');");
            stmt.executeUpdate("INSERT INTO department_of VALUES ('10000000001', 'Internal Medicine');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('010','2021-01-01', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('011','2021-01-01', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('020','2021-01-02', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('021','2021-01-02', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('030','2021-01-03', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('031','2021-01-03', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('040','2021-01-04', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('041','2021-01-04', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('050','2021-01-05', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('051','2021-01-05', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('060','2021-01-06', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('061','2021-01-06', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('070','2021-01-07', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('071','2021-01-07', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('080','2021-01-08', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('081','2021-01-08', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('090','2021-01-09', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('091','2021-01-09', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('100','2021-01-10', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('101','2021-01-10', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('110','2021-01-11', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('111','2021-01-11', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('120','2021-01-12', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('121','2021-01-12', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('130','2021-01-13', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('131','2021-01-13', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('140','2021-01-14', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('141','2021-01-14', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('150','2021-01-15', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('151','2021-01-15', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('160','2021-01-16', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('161','2021-01-16', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('170','2021-01-17', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('171','2021-01-17', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('180','2021-01-18', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('181','2021-01-18', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('190','2021-01-19', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('191','2021-01-19', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('200','2021-01-20', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('201','2021-01-20', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('210','2021-01-21', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('211','2021-01-21', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('220','2021-01-22', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('221','2021-01-22', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('230','2021-01-23', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('231','2021-01-23', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('240','2021-01-24', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('241','2021-01-24', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('250','2021-01-25', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('251','2021-01-25', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('260','2021-01-26', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('261','2021-01-26', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('270','2021-01-27', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('271','2021-01-27', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('280','2021-01-28', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('281','2021-01-28', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('290','2021-01-29', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('291','2021-01-29', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('300','2021-01-30', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('301','2021-01-30', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule_of VALUES ('010','10000000000');");
            stmt.executeUpdate("INSERT INTO schedule_of VALUES ('020','10000000001');");
            stmt.executeUpdate("INSERT INTO schedule_of VALUES ('051','10000000000');");
            stmt.executeUpdate("INSERT INTO schedule_of VALUES ('081','10000000000');");
            stmt.executeUpdate("INSERT INTO schedule_of VALUES ('081','10000000001');");
            stmt.executeUpdate("INSERT INTO appointment VALUES ('10000000000','2021-01-05');");
            stmt.executeUpdate("INSERT INTO appointment VALUES ('10000000001','2021-01-08');");
            stmt.executeUpdate("INSERT INTO appointment VALUES ('10000000002','2021-01-08');");
            stmt.executeUpdate("INSERT INTO appointment_of VALUES ('10000000000','20000000000', '10000000000');");
            stmt.executeUpdate("INSERT INTO appointment_of VALUES ('10000000001','20000000000', '10000000000');");
            stmt.executeUpdate("INSERT INTO appointment_of VALUES ('10000000002','20000000000', '10000000001');");







            con.close();
        } catch (SQLException se){
            se.printStackTrace();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}