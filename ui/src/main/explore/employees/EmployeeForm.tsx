import React, { useState } from 'react';
import {
  Container, Row, Col, Card, Button, Form,
} from 'react-bootstrap';
import { EmployeeState } from '../../../types/DomainTypes';

export function EmployeeForm(data?: Partial<EmployeeState>) {
  const [employee, setEmployee] = useState(data);

  const handleInputChange = (event: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { target } = event;
    const { name } = target;
    const { value } = target;

    const employeeState: Partial<EmployeeState> = {
      [name]: value,
    };

    setEmployee(employeeState);
  };

  return (
    <Card>
      <Card.Header>Employees management</Card.Header>
      <Card.Body>
        <Card.Text>
          Here you can manage the employees.
        </Card.Text>
        <Container>
          <Row>
            <Col>
              <Form>
                <Form.Row>
                  <Form.Group as={Col} controlId="formGridFirstName">
                    <Form.Label>First name</Form.Label>
                    <Form.Control name="firstName" onChange={handleInputChange} value={employee?.firstName} type="text" placeholder="Enter first name" />
                  </Form.Group>

                  <Form.Group as={Col} controlId="formGridLastName">
                    <Form.Label>Last name</Form.Label>
                    <Form.Control name="lastName" onChange={handleInputChange} value={employee?.lastName} type="text" placeholder="Enter last name" />
                  </Form.Group>
                </Form.Row>

                <Form.Row>
                  <Form.Group controlId="formGridSalary">
                    <Form.Label>Salary</Form.Label>
                    <Form.Control value={employee?.salary} onChange={handleInputChange} placeholder="The Salary" />
                  </Form.Group>
                  <Form.Group as={Col} controlId="formGridState">
                    <Form.Label>Department</Form.Label>
                    <Form.Control as="select" defaultValue="Choose..." value={employee?.departmentId}>
                      <option value="1">Development</option>
                      <option value="1">Development</option>
                    </Form.Control>
                  </Form.Group>
                </Form.Row>
              </Form>
            </Col>
          </Row>
        </Container>
      </Card.Body>
      <Card.Footer>
        <Button variant="primary" style={{ float: 'right' }}>
          Save
        </Button>
      </Card.Footer>
    </Card>
  );
}
