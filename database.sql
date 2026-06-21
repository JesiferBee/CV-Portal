CREATE DATABASE IF NOT EXISTS cv_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cv_portal;

CREATE TABLE IF NOT EXISTS profiles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(150) NOT NULL,
    job_title VARCHAR(120) NOT NULL,
    photo VARCHAR(255) NOT NULL,
    email VARCHAR(120) NOT NULL,
    phone VARCHAR(40) NOT NULL,
    address VARCHAR(255) NOT NULL,
    about_me TEXT NOT NULL,
    education TEXT NOT NULL,
    experience TEXT NOT NULL,
    skills TEXT NOT NULL,
    languages TEXT NOT NULL,
    github VARCHAR(255) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO profiles (fullname, job_title, photo, email, phone, address, about_me, education, experience, skills, languages, github) VALUES
('Amina Khan', 'UI/UX Designer', 'assets/images/default-profile.svg', 'amina.khan@example.com', '+1 555 012 3456', '83 Sunset Blvd, San Diego, CA', 'Creative designer with 6 years of experience building clean and accessible interfaces for web and mobile.', 'B.Des. in Visual Communication, California Institute of the Arts (2015-2019)\nCertificate in Human-Centered Design, Nielsen Norman Group (2021)', 'Senior Product Designer, PixelForge Studio (2021-present)\nUX Designer, BrightWave Digital (2019-2021)', 'Figma, Sketch, Adobe XD, Web Accessibility, Prototyping, Illustration', 'English, Spanish', 'https://github.com/aminakhan'),
('Luca Romano', 'Full Stack Developer', 'assets/images/default-profile.svg', 'luca.romano@example.com', '+44 20 7946 0123', '14 Park Lane, London, UK', 'Full stack developer passionate about building scalable web applications with modern PHP, JavaScript and cloud-first tools.', 'B.Sc. in Computer Science, University College London (2014-2018)\nProfessional Certificate in Cloud Development, AWS (2022)', 'Lead Developer, NovaTech Solutions (2022-present)\nWeb Developer, Horizon Labs (2018-2022)', 'PHP, Laravel, React, MySQL, REST APIs, Docker, Testing', 'English, Italian', 'https://github.com/lucaromano'),
('Mia Santos', 'Digital Marketing Specialist', 'assets/images/default-profile.svg', 'mia.santos@example.com', '+61 2 9876 5432', '220 King St, Sydney, Australia', 'Marketing specialist skilled in content strategy, performance campaigns, and brand storytelling for tech and lifestyle brands.', 'B.A. in Marketing, University of Sydney (2016-2020)\nGoogle Analytics Certified (2021)', 'Digital Marketing Lead, Bright Horizon Media (2021-present)\nMarketing Coordinator, Spark Social Agency (2020-2021)', 'SEO, Content Strategy, Campaign Analytics, Social Media Advertising, Email Marketing', 'English, Portuguese', 'https://github.com/miasantos');
