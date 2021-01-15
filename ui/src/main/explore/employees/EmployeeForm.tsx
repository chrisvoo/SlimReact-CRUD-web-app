import React, { useState, useEffect } from 'react';
import {
  Container, Row, Col, Card, Button, Form, Alert,
} from 'react-bootstrap';
import axios from 'axios';
import { Redirect, useLocation } from 'react-router-dom';
import { useForm /* , Controller */ } from 'react-hook-form';
import { Employee, Department } from '../../../types/DomainTypes';
import ApiCalls from '../../shared/ApiCalls';

export function EmployeeForm() {
  const [isLoading, setLoading] = useState(false);
  const [successSubmit, setSuccessSubmit] = useState(false);
  const [errorResponse, setErrorResponse] = useState({ error: false, description: '' });
  const [departments, setDepartments] = useState([]);
  const { pathname } = useLocation();
  const {
    handleSubmit, register, errors, setValue,
  } = useForm();

  const lastRouteParam = pathname.substring(pathname.lastIndexOf('/') + 1);

  useEffect(() => {
    if (lastRouteParam !== 'create') {
      ApiCalls.getEmployee(lastRouteParam,
        (response: any) => {
          const {
            firstName, lastName, salary, departmentId,
          } = response.data as Employee;
          setValue('firstName', firstName);
          setValue('lastName', lastName);
          setValue('salary', salary);
          setValue('departmentId', departmentId);
        },
        (error: Error) => {
          setErrorResponse({
            error: true,
            description: error.message,
          });
        });
    }

    ApiCalls.getDepartments(
      (result) => setDepartments(result.data),
      (error: Error) => {
        setErrorResponse({
          error: true,
          description: error.message,
        });
      },
    );
  }, []);

  // eslint-disable-next-line no-console
  const onSubmit = (emp: Employee) => {
    setLoading(true);

    // we manage both creation and modification of the department
    axios({
      method: lastRouteParam === 'create' ? 'post' : 'put',
      url: `/api/employee${lastRouteParam === 'create' ? '' : `/${lastRouteParam}`}`,
      data: {
        firstName: emp.firstName,
        lastName: emp.lastName,
        salary: emp.salary,
        departmentId: emp.departmentId,
      },
    }).then(() => {
      setSuccessSubmit(true);
    })
      .catch((error) => {
        setErrorResponse({
          error: true,
          description: error.message,
        });
      })
      .finally(() => {
        setLoading(false);
      });
  };

  if (successSubmit) {
    return <Redirect to="/employees" />;
  }

  return (
    <Card>
      <Card.Header>Employees management</Card.Header>
      <Card.Body>
        <Card.Text>
          Here you can manage the employees.
        </Card.Text>
        <Container>
          <Form noValidate onSubmit={handleSubmit(onSubmit)}>
            <Row>
              <Col>
                <Form.Row>
                  <Form.Group as={Col} controlId="formGridFirstName">
                    <Form.Label>First name</Form.Label>
                    <Form.Control
                      name="firstName"
                      ref={register({ required: true })}
                      type="text"
                      placeholder="Enter first name"
                      isInvalid={errors.firstName}
                    />
                    <Form.Control.Feedback
                      type="invalid"
                    >
                      The first name is required
                    </Form.Control.Feedback>
                  </Form.Group>

                  <Form.Group as={Col} controlId="formGridLastName">
                    <Form.Label>Last name</Form.Label>
                    <Form.Control
                      name="lastName"
                      ref={register({ required: true })}
                      type="text"
                      placeholder="Enter last name"
                      isInvalid={errors.lastName}
                    />
                    <Form.Control.Feedback
                      type="invalid"
                    >
                      The last name is required
                    </Form.Control.Feedback>
                  </Form.Group>
                </Form.Row>
                <Form.Row>
                  <Form.Group as={Col} controlId="formGridSalary">
                    <Form.Label>Salary</Form.Label>
                    <Form.Control
                      name="salary"
                      ref={register({ required: true })}
                      type="text"
                      placeholder="Enter salary"
                      isInvalid={errors.salary}
                    />
                    <Form.Control.Feedback
                      type="invalid"
                    >
                      The salary is required
                    </Form.Control.Feedback>
                  </Form.Group>

                  <Form.Group as={Col} controlId="formGridDepartment">
                    <Form.Label>Department</Form.Label>
                    <Form.Control
                      name="departmentId"
                      ref={register({ required: true })}
                      as="select"
                      defaultValue="Choose..."
                      isInvalid={errors.departmentId}
                    >
                      {
                        departments.map((d: Department) => (
                          <option key={d.id} value={d.id}>
                            {d.name}
                          </option>
                        ))
                      }
                    </Form.Control>
                    <Form.Control.Feedback
                      type="invalid"
                    >
                      The department is required
                    </Form.Control.Feedback>
                  </Form.Group>
                </Form.Row>
              </Col>
            </Row>
            <Row>
              <Col>
                <Button
                  variant="primary"
                  style={{ float: 'right' }}
                  type="submit"
                  disabled={isLoading}
                >
                  {isLoading ? 'Loading…' : 'Save'}
                </Button>
              </Col>
            </Row>
          </Form>
        </Container>
      </Card.Body>
      <Card.Footer>
        { errorResponse.error && (
        <Alert variant="danger">
          {errorResponse.description}
        </Alert>
        )}
      </Card.Footer>
    </Card>
  );
}
