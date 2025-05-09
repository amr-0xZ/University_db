-- Create the database
CREATE DATABASE IF NOT EXISTS university_db;
USE university_db;

-- Department table
CREATE TABLE department (
    department_id VARCHAR(10) PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL,
    building VARCHAR(50) NOT NULL,
    budget DECIMAL(12, 2) NOT NULL,
    established_date DATE NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL
);

-- Professor table
CREATE TABLE professor (
    professor_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    office_location VARCHAR(50) NOT NULL,
    office_hours VARCHAR(100),
    hire_date DATE NOT NULL,
    salary DECIMAL(10, 2) NOT NULL,
    `rank` ENUM('Assistant', 'Associate', 'Full') NOT NULL,
    department_id VARCHAR(10) NOT NULL,
    FOREIGN KEY (department_id) REFERENCES department(department_id)
);

-- Student table (modified to remove minor_department_id)
CREATE TABLE student (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    address VARCHAR(200) NOT NULL,
    enrollment_date DATE NOT NULL,
    expected_graduation DATE,
    status ENUM('Active', 'On Leave', 'Graduated', 'Withdrawn') DEFAULT 'Active',
    major_department_id VARCHAR(10) NOT NULL,
    FOREIGN KEY (major_department_id) REFERENCES department(department_id)
);

-- Course table
CREATE TABLE course (
    course_id VARCHAR(10) PRIMARY KEY,
    course_name VARCHAR(100) NOT NULL,
    description TEXT,
    credits TINYINT NOT NULL,
    prerequisites VARCHAR(200),
    department_id VARCHAR(10) NOT NULL,
    level ENUM('100', '200', '300', '400', 'Graduate') NOT NULL,
    FOREIGN KEY (department_id) REFERENCES department(department_id)
);

-- Section table
CREATE TABLE section (
    section_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id VARCHAR(10) NOT NULL,
    professor_id INT NOT NULL,
    semester VARCHAR(20) NOT NULL,
    schedule VARCHAR(100) NOT NULL,
    classroom VARCHAR(50) NOT NULL,
    capacity INT NOT NULL,
    enrolled_count INT DEFAULT 0,
    FOREIGN KEY (course_id) REFERENCES course(course_id),
    FOREIGN KEY (professor_id) REFERENCES professor(professor_id)
);

-- Enrollment table
CREATE TABLE enrollment (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    section_id INT NOT NULL,
    enrollment_date DATE NOT NULL,
    grade ENUM('A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'F', 'W', 'I') DEFAULT NULL,
    status ENUM('Registered', 'Dropped', 'Completed') DEFAULT 'Registered',
    FOREIGN KEY (student_id) REFERENCES student(student_id),
    FOREIGN KEY (section_id) REFERENCES section(section_id),
    UNIQUE KEY (student_id, section_id)
);

-- Insert 10 sample departments
INSERT INTO department VALUES
('CS', 'Computer Science', 'Engineering Building', 500000.00, '1980-01-15', '555-1001', 'cs@university.edu'),
('MATH', 'Mathematics', 'Science Building', 350000.00, '1975-08-20', '555-1002', 'math@university.edu'),
('ENG', 'English', 'Humanities Building', 300000.00, '1965-05-10', '555-1003', 'english@university.edu'),
('PHYS', 'Physics', 'Science Building', 400000.00, '1970-03-12', '555-1004', 'physics@university.edu'),
('CHEM', 'Chemistry', 'Science Building', 380000.00, '1972-07-18', '555-1005', 'chemistry@university.edu'),
('BIO', 'Biology', 'Science Building', 420000.00, '1968-09-22', '555-1006', 'biology@university.edu'),
('HIST', 'History', 'Humanities Building', 280000.00, '1960-11-05', '555-1007', 'history@university.edu'),
('PSY', 'Psychology', 'Social Sciences Building', 320000.00, '1978-04-30', '555-1008', 'psychology@university.edu'),
('ECON', 'Economics', 'Business Building', 360000.00, '1973-10-15', '555-1009', 'economics@university.edu'),
('ART', 'Art', 'Fine Arts Building', 250000.00, '1963-02-28', '555-1010', 'art@university.edu');

-- Insert 10 sample professors
INSERT INTO professor VALUES
(NULL, 'John', 'Smith', '1975-03-22', 'Male', 'jsmith@university.edu', '555-2001', 'ENG-205', 'MWF 10-12', '2005-08-15', 85000.00, 'Associate', 'CS'),
(NULL, 'Sarah', 'Johnson', '1980-07-14', 'Female', 'sjohnson@university.edu', '555-2002', 'MATH-310', 'TTh 1-3', '2010-01-10', 92000.00, 'Full', 'MATH'),
(NULL, 'Michael', 'Williams', '1985-11-05', 'Male', 'mwilliams@university.edu', '555-2003', 'ENG-150', 'MW 2-4', '2015-08-20', 75000.00, 'Assistant', 'ENG'),
(NULL, 'Emily', 'Brown', '1978-05-19', 'Female', 'ebrown@university.edu', '555-2004', 'PHYS-220', 'MWF 9-11', '2008-09-01', 88000.00, 'Associate', 'PHYS'),
(NULL, 'David', 'Jones', '1972-09-30', 'Male', 'djones@university.edu', '555-2005', 'CHEM-315', 'TTh 10-12', '2003-07-15', 95000.00, 'Full', 'CHEM'),
(NULL, 'Jennifer', 'Davis', '1983-12-08', 'Female', 'jdavis@university.edu', '555-2006', 'BIO-210', 'MWF 1-3', '2012-03-20', 82000.00, 'Associate', 'BIO'),
(NULL, 'Robert', 'Miller', '1970-02-14', 'Male', 'rmiller@university.edu', '555-2007', 'HIST-110', 'TTh 2-4', '2000-01-10', 78000.00, 'Full', 'HIST'),
(NULL, 'Lisa', 'Wilson', '1982-08-25', 'Female', 'lwilson@university.edu', '555-2008', 'PSY-305', 'MW 9-11', '2011-09-05', 83000.00, 'Associate', 'PSY'),
(NULL, 'James', 'Taylor', '1976-04-03', 'Male', 'jtaylor@university.edu', '555-2009', 'ECON-215', 'TTh 9-11', '2007-08-20', 90000.00, 'Full', 'ECON'),
(NULL, 'Amanda', 'Anderson', '1988-01-17', 'Female', 'aanderson@university.edu', '555-2010', 'ART-120', 'MWF 2-4', '2018-01-15', 70000.00, 'Assistant', 'ART');

-- Insert 10 sample students (without minor department)
INSERT INTO student VALUES
(NULL, 'Emily', 'Davis', '2000-05-18', 'Female', 'edavis@student.university.edu', '555-3001', '123 Main St', '2020-08-25', '2024-05-15', 'Active', 'CS'),
(NULL, 'Daniel', 'Brown', '1999-09-30', 'Male', 'dbrown@student.university.edu', '555-3002', '456 Oak Ave', '2019-08-20', '2023-05-15', 'Active', 'MATH'),
(NULL, 'Olivia', 'Miller', '2001-02-12', 'Female', 'omiller@student.university.edu', '555-3003', '789 Pine Rd', '2021-01-15', '2025-05-15', 'Active', 'ENG'),
(NULL, 'William', 'Wilson', '2000-07-23', 'Male', 'wwilson@student.university.edu', '555-3004', '321 Elm St', '2020-08-25', '2024-05-15', 'Active', 'PHYS'),
(NULL, 'Sophia', 'Moore', '1999-11-15', 'Female', 'smoore@student.university.edu', '555-3005', '654 Maple Dr', '2019-08-20', '2023-05-15', 'Active', 'CHEM'),
(NULL, 'Benjamin', 'Taylor', '2001-03-08', 'Male', 'btaylor@student.university.edu', '555-3006', '987 Cedar Ln', '2021-01-15', '2025-05-15', 'Active', 'BIO'),
(NULL, 'Ava', 'Anderson', '2000-08-19', 'Female', 'aanderson@student.university.edu', '555-3007', '159 Birch Blvd', '2020-08-25', '2024-05-15', 'Active', 'HIST'),
(NULL, 'Ethan', 'Thomas', '1999-12-05', 'Male', 'ethomas@student.university.edu', '555-3008', '753 Willow Way', '2019-08-20', '2023-05-15', 'Active', 'PSY'),
(NULL, 'Mia', 'Jackson', '2001-04-27', 'Female', 'mjackson@student.university.edu', '555-3009', '357 Spruce Ct', '2021-01-15', '2025-05-15', 'Active', 'ECON'),
(NULL, 'Alexander', 'White', '2000-09-14', 'Male', 'awhite@student.university.edu', '555-3010', '852 Redwood Ave', '2020-08-25', '2024-05-15', 'Active', 'ART');

-- Insert 10 sample courses
INSERT INTO course VALUES
('CS101', 'Introduction to Computer Science', 'Fundamentals of programming and algorithms', 4, NULL, 'CS', '100'),
('MATH201', 'Calculus I', 'Differential and integral calculus', 3, NULL, 'MATH', '200'),
('ENG102', 'Composition and Rhetoric', 'College-level writing skills', 3, NULL, 'ENG', '100'),
('PHYS210', 'General Physics I', 'Mechanics, heat, and waves', 4, NULL, 'PHYS', '200'),
('CHEM101', 'General Chemistry', 'Basic principles of chemistry', 4, NULL, 'CHEM', '100'),
('BIO202', 'Cell Biology', 'Structure and function of cells', 3, 'BIO101', 'BIO', '200'),
('HIST110', 'World History I', 'Ancient civilizations to 1500', 3, NULL, 'HIST', '100'),
('PSY105', 'Introduction to Psychology', 'Basic psychological principles', 3, NULL, 'PSY', '100'),
('ECON201', 'Principles of Economics', 'Micro and macroeconomic theory', 3, NULL, 'ECON', '200'),
('ART120', 'Drawing Fundamentals', 'Basic drawing techniques', 3, NULL, 'ART', '100');

-- Insert 10 sample sections
INSERT INTO section VALUES
(NULL, 'CS101', 1, 'Fall 2023', 'MWF 10:00-10:50', 'ENG-205', 30, 25),
(NULL, 'MATH201', 2, 'Fall 2023', 'TTh 1:00-2:15', 'MATH-310', 25, 20),
(NULL, 'ENG102', 3, 'Fall 2023', 'MW 2:00-3:15', 'ENG-150', 20, 18),
(NULL, 'PHYS210', 4, 'Fall 2023', 'MWF 9:00-9:50', 'PHYS-220', 25, 22),
(NULL, 'CHEM101', 5, 'Fall 2023', 'TTh 10:00-11:15', 'CHEM-315', 30, 28),
(NULL, 'BIO202', 6, 'Fall 2023', 'MWF 1:00-1:50', 'BIO-210', 20, 18),
(NULL, 'HIST110', 7, 'Fall 2023', 'TTh 2:00-3:15', 'HIST-110', 25, 20),
(NULL, 'PSY105', 8, 'Fall 2023', 'MW 9:00-10:15', 'PSY-305', 30, 25),
(NULL, 'ECON201', 9, 'Fall 2023', 'TTh 9:00-10:15', 'ECON-215', 25, 22),
(NULL, 'ART120', 10, 'Fall 2023', 'MWF 2:00-2:50', 'ART-120', 15, 12);

-- Insert 10 sample enrollments
INSERT INTO enrollment VALUES
(NULL, 1, 1, '2023-08-28', 'A', 'Completed'),
(NULL, 1, 2, '2023-08-28', 'B+', 'Completed'),
(NULL, 2, 2, '2023-08-28', 'A-', 'Completed'),
(NULL, 3, 3, '2023-08-28', 'B', 'Completed'),
(NULL, 4, 4, '2023-08-28', 'A', 'Completed'),
(NULL, 5, 5, '2023-08-28', 'B-', 'Completed'),
(NULL, 6, 6, '2023-08-28', 'C+', 'Completed'),
(NULL, 7, 7, '2023-08-28', 'A-', 'Completed'),
(NULL, 8, 8, '2023-08-28', 'B+', 'Completed'),
(NULL, 9, 9, '2023-08-28', 'A', 'Completed');