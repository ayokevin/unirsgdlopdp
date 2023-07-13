import { React, useEffect, useState, useContext } from 'react'
import { fetchJson } from '../../hooks/useFetchJson';
import Modal from '../Modal/Modal';
import Select from 'react-select';
import { AuthContext } from '../Context/AuthContext';
import { Notify } from '../Modal/Notify';

export const UpdatActions = ({ selectedDataRow, closeModalEdit }) => {

    const { loginData } = useContext(AuthContext);
    const [selectedDataReference, setSelectedDataReference] = useState({ value: selectedDataRow.status_id, label: selectedDataRow.reference_name });
    const [selectedDepartment, setSelectedDepartment] = useState({ value: selectedDataRow.department_id, label: selectedDataRow.department_name });
    const [department, setDepartment] = useState([]);
    const [references, setReferences] = useState([]);

    const [data, setData] = useState({
        actionId: selectedDataRow.action_id,
        _status: selectedDataRow.status_id,
        actionName: selectedDataRow.action_name,
        actionDescription: selectedDataRow.action_description,
        _department: selectedDataRow.department_id
    });
    const [showNotifyError, setNotifyError] = useState(false);
    const [showNotifySuccess, setNotifySuccess] = useState(false);

    const fetchDataReference = async () => {
        try {
            const requestData = {
                referenceTableName: 'system.action',
                referenceField: 'status_id',
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiReference/apiReference.php/listReference',
                requestData
            );

            setReferences(responseData.data);
        } catch (error) {
            console.error(error);
        }
    };

    const fetchDataDepartment = async () => {
        try {
            const requestData = {
                idClient: loginData.clientId
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiDepartment/apiDepartment.php/listDepartment',
                requestData
            );

            setDepartment(responseData.data);
        } catch (error) {
            console.error(error);
        }
    };

    useEffect(() => {
        fetchDataDepartment();
        fetchDataReference();
    }, []);

    const handleUpdate = async () => {
        try {

            if (data._status === '' || data.actionName === '' || data.actionDescription === '' || data._department === '') {
                handleShowError();
                return;
            }

            const requestData = {
                actionId: selectedDataRow.action_id,
                _status: data._status,
                actionName: data.actionName,
                actionDescription: data.actionDescription,
                _department: data._department
            };
            const responseData = await fetchJson(
                'http://localhost:3000/app/apiAction/apiAction.php/updateAction',
                requestData
            );

            handleShowSuccess();
            setTimeout(() => {
                closeModalEdit();
            }, 2000);

        } catch (error) {
            console.error(error);
        }
    };

    const handleChangeName = (event) => {
        setData({ ...data, actionName: event.target.value });
    };

    const handleChangeDetail = (event) => {
        setData({ ...data, actionDescription: event.target.value });
    };

    const optionsReference = references.map((data) => ({
        value: data.reference_id,
        label: data.reference_name,
    }));

    const optionsDepartment = department.map((data) => ({
        value: data.department_id,
        label: data.department_name,
    }));

    const handleChangeDepartment = (event) => {
        setSelectedDepartment(event || '');
        if (event && event.value) {
            setData({ ...data, _department: event.value });
        }
    };

    const handleChangeStatus = (event) => {
        setSelectedDataReference(event || '');
        if (event && event.value) {
            setData({ ...data, _status: event.value });
        }
    };

    const handleShowError = () => {
        setNotifyError(true);
        setTimeout(() => {
            setNotifyError(false);
        }, 5000);
    };

    const handleShowSuccess = () => {
        setNotifySuccess(true);
        setTimeout(() => {
            setNotifySuccess(false);
        }, 2000);
    };

    return (
        <Modal
            title="Modificar acción"
            body={
                <div className="card card-primary ">
                    <div className="card-header">
                        <h3 className="card-title">Datos de la acción</h3>
                        {showNotifyError && <Notify message="Todos los campos son obligatorios" type="error" />}
                        {showNotifySuccess && <Notify message="Datos guardados" type="success" />}
                    </div>
                    <div className="card-body ">
                        <form>
                            <div className="row">
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Id Acción</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            defaultValue={selectedDataRow.action_id}
                                            disabled
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Nombre</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            defaultValue={selectedDataRow.action_name}
                                            placeholder="Enter ..."
                                            onChange={handleChangeName}
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Detalle</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            defaultValue={selectedDataRow.action_description}
                                            placeholder="Enter ..."
                                            onChange={handleChangeDetail}
                                        />
                                    </div>
                                </div>
                                <div className="form-group col-sm-12">
                                    <label>Departamento</label>
                                    <Select
                                        onChange={handleChangeDepartment}
                                        options={optionsDepartment}
                                        placeholder={'Seleccione un estado'}
                                        value={selectedDepartment}
                                    ></Select>
                                </div>
                                <div className="form-group col-sm-12">
                                    <label>Estado</label>
                                    <Select
                                        onChange={handleChangeStatus}
                                        options={optionsReference}
                                        placeholder={'Seleccione un estado'}
                                        defaultValue={selectedDataReference}
                                    ></Select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            }
            button1Name="Cerrar"
            button2Name="Guardar Cambios"
            handleButton1Click={closeModalEdit}
            handleButton2Click={handleUpdate}
            closeModalEdit={closeModalEdit}
        />
    )
}






