import React, { useEffect, useState } from 'react';
import axios from 'axios';
import {
  Container, Row, Col, Table, Card,
} from 'react-bootstrap';
import { Loading } from '../shared/Loading';
import { ReportResponse, ReportCardState } from '../../types/ReportTypes';

export const ExpensiveDepartments = () => {
  const [appState, setAppState] = useState<ReportCardState>({
    loading: false,
    reportItems: [],
  });

  useEffect(() => {
    setAppState({ loading: true, reportItems: [] });
    axios.get<ReportResponse[]>('/api/report/expensive_departments')
      .then((result) => {
        setAppState({ loading: false, reportItems: result.data });
      });
  }, [setAppState]);

  const { loading, reportItems } = appState;

  if (loading) {
    return <Loading />;
  }

  return (
    <Card>
      <Card.Header>Expensive Departments</Card.Header>
      <Card.Body>
        <Card.Text>
          This report only shows the departments that have more than 2 employees have more than two employees that earn over 50,000.
        </Card.Text>
        <Container>
          <Row>
            <Col>
              <Table responsive striped hover variant="dark">
                <thead>
                  <th>Department</th>
                  <th>Num. employees</th>
                </thead>
                <tbody>
                  {
                    reportItems.map((row: ReportResponse) => <tr><td>{row.name}</td><td>{row.num_employees}</td></tr>)
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
