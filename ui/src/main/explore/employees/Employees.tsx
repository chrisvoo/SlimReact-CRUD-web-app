import React, { useEffect, useState } from 'react';
import axios from 'axios';
import {
  Container, Row, Col, Table, Card,
} from 'react-bootstrap';
import { Loading } from '../../shared/Loading';
import { Employee, EmployeeCardState } from '../../../types/DomainTypes';

export const Employees = () => {
  const [appState, setAppState] = useState<EmployeeCardState>({
    loading: false,
    employees: [],
  });

  useEffect(() => {
    setAppState({ loading: true, employees: [] });
    axios.get<Employee[]>('/api/employees')
      .then((result) => {
        setAppState({ loading: false, employees: result.data });
      });
  }, [setAppState]);

  const { loading, employees } = appState;

  if (loading) {
    return <Loading />;
  }

  return (
    <Card>
      <Card.Header>Employees</Card.Header>
      <Card.Body>
        <Card.Text>
          Here you can manage the employees.
        </Card.Text>
        <Container>
          <Row>
            <Col>
              <Table responsive striped hover variant="dark">
                <thead>
                  <th>id</th>
                  <th>First name</th>
                  <th>Last name</th>
                  <th>Salary</th>
                  <th>Department Id</th>
                </thead>
                <tbody>
                  {
                    employees.map((row: Employee) => (
                      <tr>
                        <td>{row.id}</td>
                        <td>{row.firstName}</td>
                        <td>{row.lastName}</td>
                        <td>{row.salary}</td>
                        <td>{row.departmentId}</td>
                      </tr>
                    ))
                  }
                </tbody>
              </Table>
            </Col>
          </Row>
        </Container>
      </Card.Body>
    </Card>
  );
};
