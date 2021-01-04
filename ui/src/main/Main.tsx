import React from 'react';
import {
  Switch,
  Route,
} from 'react-router-dom';
import { WelcomeCard } from './WelcomeCard';
import { ExpensiveDepartments } from './reports/ExpensiveDepartments';
import { HighestSalaries } from './reports/HighestSalaries';
import { Departments } from './explore/departments/Departments';
import { Employees } from './explore/employees/Employees';
import { EmployeeForm } from './explore/employees/EmployeeForm';
import { DepartmentForm } from './explore/departments/DepartmentForm';

export function Main() {
  return (
    <main role="main" className="container">
      <div className="row">
        <div className="col-sm-12">
          <Switch>
            <Route path="/" exact>
              <WelcomeCard />
            </Route>
            <Route path="/departments" exact>
              <Departments />
            </Route>
            <Route path="/employees" exact>
              <Employees />
            </Route>
            {/* eslint-disable-next-line react/jsx-props-no-spreading */}
            <Route path="/employee/*" component={EmployeeForm} />
            <Route path="/department/*" component={DepartmentForm} />
            <Route path="/report/expensive_departments" exact>
              <ExpensiveDepartments />
            </Route>
            <Route path="/report/highest_salaries" exact>
              <HighestSalaries />
            </Route>
          </Switch>
        </div>
      </div>
    </main>
  );
}
