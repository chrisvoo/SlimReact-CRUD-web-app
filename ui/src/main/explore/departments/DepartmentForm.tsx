import React, { useState } from 'react';
import {
  Container, Row, Col, Card, Button, Form,
} from 'react-bootstrap';
import { DepartmentState } from '../../../types/DomainTypes';

export function DepartmentForm(data?: Partial<DepartmentState>) {
  const [department, setDepartment] = useState(data);

  const handleInputChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    setDepartment({
      name: event.target.value,
    });
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
                  <Form.Group as={Col} controlId="formGridName">
                    <Form.Label>First name</Form.Label>
                    <Form.Control name="name" onChange={handleInputChange} value={department?.name} type="text" placeholder="Enter name" />
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
