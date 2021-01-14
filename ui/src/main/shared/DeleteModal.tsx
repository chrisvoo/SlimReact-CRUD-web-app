import React, { useEffect, useState } from 'react';
import { Modal, Button } from 'react-bootstrap';
import { DeleteModalParams, DeleteModalEntity } from '../../types/DomainTypes';
import ApiCalls from './ApiCalls';

export default function DeleteModal(props: DeleteModalParams) {
  const {
    show, id, entityName, resetState,
    onSuccess, onError, // callbacks
    entityType,
  } = props;
  const [showDeleteModal, setShowDeleteModal] = useState(show);

  useEffect(() => {
    setShowDeleteModal(show);
  }, [show, id]);

  const handleCloseDeleteModal = () => {
    setShowDeleteModal(false);
    resetState();
  };

  const deleteDepartment = (depId: number | string) => {
    ApiCalls.deleteDepartment(depId, onSuccess, onError);
  };

  const deleteEmployee = (empId: number | string) => {
    ApiCalls.deleteEmployee(empId, onSuccess, onError);
  };

  const deleteCall = (entityType === DeleteModalEntity.DEPARTMENT)
    ? deleteDepartment
    : deleteEmployee;

  return (
    <Modal show={showDeleteModal} onHide={handleCloseDeleteModal}>
      <Modal.Header closeButton>
        <Modal.Title>Delete confirm</Modal.Title>
      </Modal.Header>
      <Modal.Body>Are you sure to delete {entityName}?</Modal.Body>
      <Modal.Footer>
        <Button variant="secondary" onClick={handleCloseDeleteModal}>
          Close
        </Button>
        <Button variant="danger" onClick={() => deleteCall(id)}>
          DELETE
        </Button>
      </Modal.Footer>
    </Modal>
  );
}
