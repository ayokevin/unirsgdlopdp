import { React, useEffect, useState } from 'react'
import { fetchJson } from '../../hooks/useFetchJson';
import Modal from '../Modal/Modal';
import Select from 'react-select';
import { Notify } from '../Modal/Notify';


export const UpdateUserSec = ({ selectedDataRow, closeModalEdit }) => {

    const [selectedDataClient, setSelectedDataClient] = useState({ value: selectedDataRow.client_id, label: selectedDataRow.client_name });
    const [selectedDataReference, setSelectedDataReference] = useState({ value: selectedDataRow.status_id, label: selectedDataRow.reference_name });
    const [selectedDataAccess, setSelectedDataAccess] = useState({ value: selectedDataRow.user_application, label: selectedDataRow.user_application });
    const [clients, setClients] = useState([]);
    const [references, setReferences] = useState([]);

    const [data, setData] = useState({
        _statusId: selectedDataRow.status_id,
        userEmail: selectedDataRow.user_email,
        userFirstName: selectedDataRow.user_first_name,
        userLastName: selectedDataRow.user_last_name,
        userApplication: selectedDataRow.user_application,
        userPassword: selectedDataRow.user_password,
        _clientId: selectedDataRow.client_id
    });
    const [showNotifyError, setNotifyError] = useState(false);
    const [showNotifySuccess, setNotifySuccess] = useState(false);

    const fetchDataReference = async () => {
        try {
            const requestData = {
                referenceTableName: 'security.user_sec',
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

    const fetchDataClient = async () => {
        try {
            const requestData = {};

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiClient/apiClient.php/listClient',
                requestData
            );

            setClients(responseData.data);
        } catch (error) {
            console.error(error);
        }
    };

    useEffect(() => {
        fetchDataClient();
        fetchDataReference();
    }, []);

    const handleUpdate = async () => {

        if (data._statusId === '' || data.userEmail === '' || data.userFirstName === '' || data.userLastName === '' || data.userApplication === '') {
            handleShowError();
            return;
        }

        try {

            const requestData = {
                userSecId: selectedDataRow.user_sec_id,
                _statusId: data._statusId,
                userEmail: data.userEmail,
                userFirstName: data.userFirstName,
                userLastName: data.userLastName,
                userApplication: data.userApplication,
                userPassword: data.userPassword,
                _clientId: data._clientId,
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiUserSec/apiUserSec.php/updateUserSec',
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

    const handleChangeEmail = (event) => {
        setData({ ...data, userEmail: event.target.value });
    };

    const handleChangeNombres = (event) => {
        setData({ ...data, userFirstName: event.target.value });
    };

    const handleChangeApellidos = (event) => {
        setData({ ...data, userLastName: event.target.value });
    };

    const handleChangePassword = (event) => {
        setData({ ...data, userPassword: event.target.value });
    };

    const optionsClient = clients.map((data) => ({
        value: data.client_id,
        label: data.client_name,
    }));

    const optionsReference = references.map((data) => ({
        value: data.reference_id,
        label: data.reference_name,
    }));

    const optionsAccess = [
        { value: 't', label: 't' },
        { value: 'f', label: 'f' },
    ];

    const handleChangeIdClient = (event) => {
        setSelectedDataClient(event || '');
        if (event && event.value) {
            setData({ ...data, _clientId: event.value });
        }
    };

    const handleChangeAccess = (event) => {
        setSelectedDataAccess(event || '');
        if (event && event.value) {
            setData({ ...data, userApplication: event.value });
        }
    };

    const handleChangeStatus = (event) => {
        setSelectedDataReference(event || '');
        if (event && event.value) {
            setData({ ...data, _statusId: event.value });
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
            title="Editar usuario"
            body={
                <div className="card card-primary ">
                    <div className="card-header">
                        <h3 className="card-title">Datos del usuario</h3>
                        {showNotifyError && <Notify message="Todos los campos son obligatorios" type="error" />}
                        {showNotifySuccess && <Notify message="Datos guardados" type="success" />}
                    </div>
                    <div className="card-body ">
                        <form>
                            <div className="row">
                                <div className="col-sm-6">
                                    <div className="form-group">
                                        <label>Id</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            defaultValue={selectedDataRow.user_sec_id}
                                            disabled
                                        />
                                    </div>
                                </div>
                                <div className="form-group col-sm-6">
                                    <label>Estado</label>
                                    <Select
                                        onChange={handleChangeStatus}
                                        options={optionsReference}
                                        placeholder={'Seleccione un estado'}
                                        defaultValue={selectedDataReference}
                                        isClearable
                                    ></Select>
                                </div>
                                <div className="col-sm-6">
                                    <div className="form-group">
                                        <label>Email</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            defaultValue={selectedDataRow.user_email}
                                            placeholder="Enter ..."
                                            onChange={handleChangeEmail}
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-6">
                                    <div className="form-group">
                                        <label>Nombres</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            defaultValue={selectedDataRow.user_first_name}
                                            placeholder="Enter ..."
                                            onChange={handleChangeNombres}
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-6">
                                    <div className="form-group">
                                        <label>Apellidos</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            defaultValue={selectedDataRow.user_last_name}
                                            placeholder="Enter ..."
                                            onChange={handleChangeApellidos}
                                        />
                                    </div>
                                </div>
                                <div className="form-group col-sm-6">
                                    <label>Acceso</label>
                                    <Select
                                        onChange={handleChangeAccess}
                                        options={optionsAccess}
                                        placeholder={'Seleccione un estado'}
                                        value={selectedDataAccess}
                                        isClearable
                                    ></Select>
                                </div>

                                <div className="col-sm-6">
                                    <div className="form-group">
                                        <label>Password</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            defaultValue={selectedDataRow.user_password}
                                            placeholder="Enter ..."
                                            onChange={handleChangePassword}
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-6">
                                    <div className="form-group">
                                        <label>Id Client</label>
                                        <Select
                                            onChange={handleChangeIdClient}
                                            options={optionsClient}
                                            placeholder={'Seleccione un cliente'}
                                            value={selectedDataClient}
                                            isClearable
                                        >
                                        </Select>
                                    </div>
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






