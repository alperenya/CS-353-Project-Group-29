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
                    " person_id CHAR(11) ," +
                    " date date NOT NULL," +
                    " occupation_type VARCHAR(20) NOT NULL," +
                    " PRIMARY KEY (person_id, date), " +
                    " FOREIGN KEY (person_id) references persons (person_id)) " +
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
            stmt.executeUpdate("INSERT INTO schedule VALUES ('10000000000', '2021-01-01', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('10000000001', '2021-01-02', 'Cancel');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('10000000000', '2021-01-05', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('10000000000', '2021-01-08', 'Appointment');");
            stmt.executeUpdate("INSERT INTO schedule VALUES ('10000000001', '2021-01-08', 'Appointment');");
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