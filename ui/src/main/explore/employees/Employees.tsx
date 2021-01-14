import React, { useEffect, useState } from 'react';
import { LinkContainer } from 'react-router-bootstrap';
import { Link } from 'react-router-dom';
import {
  Container, Row, Col, Table, Card, Button,
  OverlayTrigger, Tooltip, Alert,
} from 'react-bootstrap';
import { Loading } from '../../shared/Loading';
import ApiCalls from '../../shared/ApiCalls';
import DeleteModal from '../../shared/DeleteModal';
import { Employee, EmployeeCardState, DeleteModalEntity } from '../../../types/DomainTypes';

export const Employees = () => {
  const [errorResponse, setErrorResponse] = useState({ error: false, description: '' });
  const [appState, setAppState] = useState<EmployeeCardState>({
    loading: false,
    employees: [],
    showModal: false,
  });

  const handleModal = (row: Employee) => {
    setAppState({
      showModal: true,
      employees: appState.employees,
      loading: appState.loading,
      entityId: row.id,
      entityName: `${row.firstName} ${row.lastName}`,
    });
  };

  const resetState = () => setAppState({
    showModal: false,
    loading: false,
    employees: appState.employees,
  });

  const loadEmployees = () => {
    ApiCalls.getEmployees(
      (result) => {
        setAppState({
          loading: false,
          employees: result.data,
          showModal: false,
        });
        setErrorResponse({ error: false, description: '' });
      },
      (error) => setErrorResponse(error),
    );
  };

  useEffect(() => {
    setAppState({
      loading: true,
      employees: [],
      showModal: false,
    });

    loadEmployees();
  }, []);

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
                  <th>Department</th>
                  <th>Actions</th>
                </thead>
                <tbody>
                  {
                    employees.map((row: Employee) => (
                      <tr key={row.id}>
                        <td>{row.id}</td>
                        <td>{row.firstName}</td>
                        <td>{row.lastName}</td>
                        <td>{row.salary}</td>
                        <td>{row.departmentName}</td>
                        <td className="actions">
                          <OverlayTrigger
                            placement="top"
                            overlay={(
                              <Tooltip id={`tooltip-${row.id}-mod`}>
                                Modify this employee
                              </Tooltip>
                              )}
                          >
                            <Link style={{ color: 'white' }} to={`/employee/edit/${row.id}`}>
                              <i className="far fa-edit" />
                            </Link>
                          </OverlayTrigger>

                          <OverlayTrigger
                            placement="top"
                            overlay={(
                              <Tooltip id={`tooltip-${row.id}-del`}>
                                Delete this employee
                              </Tooltip>
                            )}
                          >
                            <Link style={{ color: 'white' }} to="#" onClick={() => handleModal(row)}>
                              <i className="far fa-trash-alt" />
                            </Link>
                          </OverlayTrigger>
                        </td>
                      </tr>
                    ))
                  }
                </tbody>
              </Table>
              <DeleteModal
                show={appState.showModal}
                id={appState.entityId!}
                entityType={DeleteModalEntity.EMPLOYEE}
                entityName={appState.entityName!}
                resetState={resetState}
                onSuccess={loadEmployees}
                onError={(e) => {
                  setErrorResponse({
                    error: true,
                    description: e.response.data.error.description,
                  });
                  resetState();
                }}
              />
              <LinkContainer to="/employee/create" style={{ float: 'right' }}>
                <Button
                  variant="primary"
                >
                  New
                </Button>
              </LinkContainer>
            </Col>
          </Row>
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
};
