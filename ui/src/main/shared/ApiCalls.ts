/* eslint-disable no-unused-vars */
import axios, { AxiosError } from 'axios';
import { Department, Employee, EntityID } from '../../types/DomainTypes';

export default class ApiCalls {
  static getDepartments(onSuccess: (r: any) => void, onError: (r: any) => void) {
    axios
      .get<Department[]>('/api/departments')
      .then((result) => onSuccess(result))
      .catch((e: AxiosError | Error) => {
        onError(e);
      });
  }

  static getDepartment(depId: EntityID, onSuccess: (r: any) => void, onError: (r: any) => void) {
    axios
      .get<Department[]>(`/api/department/${depId}`)
      .then((result) => onSuccess(result))
      .catch((e: AxiosError | Error) => {
        onError(e);
      });
  }

  static deleteDepartment(
    depId: EntityID,
    onSuccess: (r: any) => void,
    onError: (r: any) => void,
  ) {
    axios.delete<boolean>(`/api/department/${depId}`)
      .then((r) => onSuccess(r))
      .catch((e: AxiosError | Error) => onError(e));
  }

  static deleteEmployee(
    empId: EntityID,
    onSuccess: (r: any) => void,
    onError: (r: any) => void,
  ) {
    axios.delete<boolean>(`/api/employee/${empId}`)
      .then((r) => onSuccess(r))
      .catch((e: AxiosError | Error) => onError(e));
  }

  static getEmployees(onSuccess: (r: any) => void, onError: (r: any) => void) {
    axios.get<Employee[]>('/api/employees')
      .then((result) => onSuccess(result))
      .catch((e: AxiosError | Error) => {
        onError(e);
      });
  }

  static getEmployee(empId: EntityID, onSuccess: (r: any) => void, onError: (r: any) => void) {
    axios
      .get<Department[]>(`/api/employee/${empId}`)
      .then((result) => onSuccess(result))
      .catch((e: AxiosError | Error) => {
        onError(e);
      });
  }
}
