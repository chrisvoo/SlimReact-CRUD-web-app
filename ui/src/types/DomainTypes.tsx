export type Employee = {
  id: number,
  firstName: string
  lastName: string
  salary: number
  departmentId: number
}

export type Department = {
  id: number
  name: string
}

// form edit
export type EmployeeState = {
  [P in keyof Employee]: Employee[P]
};

export type DepartmentState = {
  [P in keyof Department]: Department[P]
};

// read
export interface EmployeeCardState {
  loading: boolean,
  employees: Employee[]
}

export interface DepartmentCardState {
  loading: boolean,
  departments: Department[]
}
