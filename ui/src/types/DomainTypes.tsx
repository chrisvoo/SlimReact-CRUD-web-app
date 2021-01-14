/* eslint-disable no-shadow */
/* eslint-disable no-unused-vars */
export type Employee = {
  id: number,
  firstName: string
  lastName: string
  salary: number
  departmentId: number
  departmentName?: string
}

export enum DeleteModalEntity {
  DEPARTMENT,
  EMPLOYEE
}

export type Department = {
  id: number
  name: string
}

export type EntityID = string | number;

// form edit
export type EmployeeState = {
  [P in keyof Employee]: Employee[P]
};

export type DepartmentState = {
  [P in keyof Department]: Department[P]
};

export interface CardState {
  loading: boolean
  showModal: boolean
  entityId?: number | string
  entityName?: string
}

export interface EmployeeCardState extends CardState {
  employees: Employee[]
}

export interface DepartmentCardState extends CardState {
  departments: Department[]
}

export type DeleteModalParams = {
  id: number | string,
  entityName: string,
  show: boolean,
  entityType: DeleteModalEntity,
  resetState: () => void
  onSuccess: (r: any) => void
  onError: (e: any) => void
}
