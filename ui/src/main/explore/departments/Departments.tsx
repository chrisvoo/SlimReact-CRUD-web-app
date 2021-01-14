import React, { useEffect, useState } from 'react';
import { LinkContainer } from 'react-router-bootstrap';
import { Link } from 'react-router-dom';
import {
  Container, Row, Col, Table, Card, Button,
  OverlayTrigger, Tooltip, Alert,
} from 'react-bootstrap';
import { Loading } from '../../shared/Loading';
import { DeleteModalEntity, Department, DepartmentCardState } from '../../../types/DomainTypes';
import DeleteModal from '../../shared/DeleteModal';
import ApiCalls from '../../shared/ApiCalls';

export const Departments = () => {
  const [errorResponse, setErrorResponse] = useState({ error: false, description: '' });
  const [appState, setAppState] = useState<DepartmentCardState>({
    loading: false,
    departments: [],
    showModal: false,
  });

  const handleModal = (row: Department) => {
    setAppState({
      showModal: true,
      departments: appState.departments,
      loading: appState.loading,
      entityId: row.id,
      entityName: row.name,
    });
  };

  const resetState = () => setAppState({
    showModal: false,
    loading: false,
    departments: appState.departments,
  });

  const loadDepartments = () => {
    ApiCalls.getDepartments(
      (result) => {
        setAppState({
          loading: false,
          departments: result.data,
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
      departments: [],
      showModal: false,
    });

    loadDepartments();
  }, []);

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
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {
                    departments.map((row: Department) => (
                      <tr key={row.id}>
                        <td>{row.id}</td>
                        <td>{row.name}</td>
                        <td className="actions">
                          <OverlayTrigger
                            placement="top"
                            overlay={(
                              <Tooltip id={`tooltip-${row.id}-mod`}>
                                Modify this department
                              </Tooltip>
                              )}
                          >
                            <Link style={{ color: 'white' }} to={`/department/edit/${row.id}`}>
                              <i className="far fa-edit" />
                            </Link>
                          </OverlayTrigger>

                          <OverlayTrigger
                            placement="top"
                            overlay={(
                              <Tooltip id={`tooltip-${row.id}-del`}>
                                Delete this department
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
                entityType={DeleteModalEntity.DEPARTMENT}
                entityName={appState.entityName!}
                resetState={resetState}
                onSuccess={loadDepartments}
                onError={(e) => {
                  setErrorResponse({
                    error: true,
                    description: e.response.data.error.description,
                  });
                  resetState();
                }}
              />
              <LinkContainer to="/department/create" style={{ float: 'right' }}>
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
