<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Department, Employee, Attendance};
use Illuminate\Support\Facades\Hash;

class EmployeeModuleSeeder extends Seeder {
    public function run(): void {
        $depts = [
            'Administration' => 'Miraz Rahman', 'Human Resources' => 'Farzana Islam',
            'Finance & Accounting' => 'Arif Chowdhury', 'Sales & Marketing' => 'Nabila Ahmed',
            'Purchase' => 'Siddiqur Rahman', 'Project Management' => 'Tanvir Hasan',
            'Property Management' => 'Imran Khan', 'Engineering' => 'Engr. Rakib Zaman',
            'IT' => 'Ahad Al Nabil'
        ];

        $departmentsMap = [];
        foreach ($depts as $name => $head) {
            $departmentsMap[$name] = Department::create([
                'name' => $name, 'dept_head' => $head, 'status' => 'Active'
            ]);
        }

        $bangladeshiNames = [
            ['first' => 'Tasnim', 'last' => 'Alam', 'gender' => 'Female', 'dept' => 'Human Resources', 'desig' => 'HR Officer'],
            ['first' => 'Mahmud', 'last' => 'Hasan', 'gender' => 'Male', 'dept' => 'Engineering', 'desig' => 'Senior Structural Engineer'],
            ['first' => 'Sultana', 'last' => 'Razia', 'gender' => 'Female', 'dept' => 'Finance & Accounting', 'desig' => 'Accounts Manager'],
            ['first' => 'Kazi', 'last' => 'Anis', 'gender' => 'Male', 'dept' => 'IT', 'desig' => 'DevOps Specialist'],
            ['first' => 'Fahmida', 'last' => 'Kamal', 'gender' => 'Female', 'dept' => 'Sales & Marketing', 'desig' => 'Executive Executive'],
            ['first' => 'Zeeshan', 'last' => 'Al-Hussain', 'gender' => 'Male', 'dept' => 'Project Management', 'desig' => 'Site Supervisor'],
            ['first' => 'Sadia', 'last' => 'Afrin', 'gender' => 'Female', 'dept' => 'Property Management', 'desig' => 'Leasing Consultant'],
            ['first' => 'Mainul', 'last' => 'Islam', 'gender' => 'Male', 'dept' => 'Purchase', 'desig' => 'Procurement Associate']
        ];

        foreach ($bangladeshiNames as $person) {
            Employee::create([
                'first_name' => $person['first'], 'last_name' => $person['last'],
                'gender' => $person['gender'], 'dob' => '1994-05-12',
                'email' => strtolower($person['first']).'@estateflow-erp.com',
                'phone' => '+8801712' . rand(100000, 999999), 'address' => 'Dhaka, Bangladesh',
                'department_id' => $departmentsMap[$person['dept']]->id, 'designation' => $person['desig'],
                'joining_date' => '2025-01-10', 'emp_type' => 'Permanent', 'salary' => rand(45000, 120000),
                'emergency_contact' => 'Spouse/Parent Contact', 'username' => strtolower($person['first']).rand(10,99),
                'password' => Hash::make('secret123'), 'status' => 'Active'
            ]);
        }

        // Complete up to 20 realistic records
        for ($i = 0; $i < 12; $i++) {
            Employee::create([
                'first_name' => 'Staff_' . $i, 'last_name' => 'Enterprise',
                'gender' => 'Male', 'dob' => '1996-08-20', 'email' => 'staff'.$i.'@estateflow-erp.com',
                'phone' => '+8801911' . rand(100000, 999999), 'address' => 'Khulna, Bangladesh',
                'department_id' => $departmentsMap['Administration']->id, 'designation' => 'Executive Support',
                'joining_date' => '2026-02-15', 'emp_type' => 'Contractual', 'salary' => 32000,
                'emergency_contact' => '+8801552000000', 'username' => 'user_sys_'.$i,
                'password' => Hash::make('secret123'), 'status' => 'Active'
            ]);
        }

        // Generate baseline check-ins for active entries
        foreach (Employee::all() as $emp) {
            Attendance::create([
                'employee_id' => $emp->id, 'attendance_date' => today(),
                'check_in' => '09:04:12', 'check_out' => '17:00:00', 'status' => rand(0, 4) == 0 ? 'Late' : 'Present'
            ]);
        }
    }
}