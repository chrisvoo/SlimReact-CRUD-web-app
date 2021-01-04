import React, { useEffect, useState } from 'react';
import axios from 'axios';
import {
  Container, Row, Col, Table, Card, Button,
} from 'react-bootstrap';
import { Loading } from '../../shared/Loading';
import { Department, DepartmentCardState } from '../../../types/DomainTypes';

export const Departments = () => {
  const [appState, setAppState] = useState<DepartmentCardState>({
    loading: false,
    departments: [],
  });

  useEffect(() => {
    setAppState({ loading: true, departments: [] });
    axios.get<Department[]>('/api/departments')
      .then((result) => {
        setAppState({ loading: false, departments: result.data });
      });
  }, [setAppState]);

  const { loading, departments } = appState;

  if (loading) {
    return <Loading />;
  }

  return (
    <Card>
      <Card.Header>Departments</Card.Header>
      <Card.Body>
        <Card.Text>
          Here you can manage the departments.
        </Card.Text>
        <Container>
          <Row>
            <Col>
              <Table responsive striped hover variant="dark">
                <thead>
                  <th>ID</th>
                  <th>Name</th>
                </thead>
                <tbody>
                  {
                    departments.map((row: Department) => (
                      <tr>
                        <td>{row.id}</td>
                        <td>{row.name}</td>
                      </tr>
                    ))
                  }
                </tbody>
              </Table>
            </Col>
          </Row>
        </Container>
      </Card.Body>
      <Card.Footer>
        <Button variant="primary" href="/department/create" style={{ float: 'right' }}>
          New
        </Button>
      </Card.Footer>
    </Card>
  );
};
