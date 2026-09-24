export type StudentStatus = 'active' | 'inactive' | 'graduated' | 'suspended';

export type EnrollmentStatus = 'enrolled' | 'completed' | 'dropped';

export type AttendanceStatus = 'present' | 'absent' | 'late' | 'excused';

export type Faculty = {
    id: number;
    name: string;
    description: string | null;
};

export type Department = {
    id: number;
    name: string;
    description?: string | null;
    faculty?: Faculty;
};

export type AcademicYear = {
    id: number;
    name: string;
    start_date: string;
    end_date: string;
    is_current: boolean;
};

export type Semester = {
    id: number;
    name: string;
    start_date?: string;
    end_date?: string;
    is_current?: boolean;
    academic_year?: AcademicYear;
};

export type Instructor = {
    id: number;
    employee_number: string;
    specialization: string | null;
    user?: { id: number; name: string; email: string };
    department?: Department;
};

export type Course = {
    id: number;
    code: string;
    name: string;
    description?: string | null;
    credit_hours: number;
    department?: Department;
    instructor?: Instructor | null;
    enrollments_count?: number;
};

export type Student = {
    id: number;
    student_number: string;
    first_name: string;
    last_name: string;
    full_name?: string;
    status: StudentStatus;
    department?: Department;
    user?: { id: number; name: string; email: string };
};

export type Grade = {
    id: number;
    enrollment_id: number;
    assignment?: number;
    midterm?: number;
    final?: number;
    total: number;
    letter_grade: string | null;
    updated_at?: string;
};

export type Enrollment = {
    id: number;
    status: EnrollmentStatus;
    enrolled_at?: string;
    student?: Student;
    course?: Course;
    semester?: Semester;
    grade?: Grade | null;
};
