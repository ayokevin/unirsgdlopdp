import { React, useEffect, useState, useContext } from 'react'
import { fetchJson } from '../../hooks/useFetchJson';
import Modal from '../Modal/Modal';
import Select from 'react-select';
import { AuthContext } from '../Context/AuthContext';
import { Notify } from '../Modal/Notify';


export const InsertEmployees = ({ closeModalInsert }) => {

    const { loginData,updateLoginData } = useContext(AuthContext);
    const [selectedDataReference, setSelectedDataReference] = useState(null);
    const [selectedDataAccess, setSelectedDataAccess] = useState(null);
    const [clients, setClients] = useState([]);
    const [references, setReferences] = useState([]);

    const [data, setData] = useState({
        _statusId: "",
        userEmail: "",
        userFirstName: "",
        userLastName: "",
        userApplication: "",
        userPassword: "",
        _clientId: loginData.clientId
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

    useEffect(() => {
        fetchDataReference();
    }, []);

    const handleInsert = async () => {

        if (data._statusId === '' || data.userEmail === '' || data.userFirstName === '' || data.userLastName === '' || data.userApplication === ''|| data.userPassword === '') {
            handleShowError();
            return;
        }

        try {
           
            const requestData = {
                _statusId: data._statusId,
                userEmail: data.userEmail,
                userFirstName: data.userFirstName,
                userLastName: data.userLastName,
                userApplication: data.userApplication,
                userPassword: data.userPassword,
                _clientId: loginData.clientId
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiUserSec/apiUserSec.php/insertUserSec',
                requestData
            );
           
            handleShowSuccess();
            setTimeout(() => {
                closeModalInsert();
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

    const optionsReference = references.map((data) => ({
        value: data.reference_id,
        label: data.reference_name,
    }));

    const optionsAccess = [
        { value: 't', label: 't' },
        { value: 'f', label: 'f' },
      ];

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
            title="Crear nuevo usuario"
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
                                            placeholder="Enter ..."
                                            onChange={handleChangePassword}
                                        />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            } // Aquí puedes pasar el contenido que desees
            button1Name="Cerrar"
            button2Name="Guardar Cambios"
            handleButton1Click={closeModalInsert}
            handleButton2Click={handleInsert}
            closeModalInsert={closeModalInsert}
        />
    )
}






