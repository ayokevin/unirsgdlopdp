import { React, useEffect, useState,useContext } from 'react'
import { fetchJson } from '../../hooks/useFetchJson';
import Modal from '../Modal/Modal';
import Select from 'react-select';
import { AuthContext } from '../Context/AuthContext';
import { Notify } from '../Modal/Notify';


export const InsertProcess = ({ closeModalInsert }) => {
    const { loginData } = useContext(AuthContext);

    const [selectedDataDepartment, setSelectedDataDepartment] = useState('');
    const [selectedDataClient, setSelectedDataClient] = useState(null);
    const [selectedDataReference, setSelectedDataReference] = useState(null);
    const [department, setDepartment] = useState([]);
    const [references, setReferences] = useState([]);
    const [userSec, setUserSec] = useState([]);
    

    const [data, setData] = useState({
        processId: "",
        userSecId: "",
        departmentId: "",
        processName: "",
        processOrder: "",
        processDescription: "",
        _statusId: ""
    });
    const [showNotifyError, setNotifyError] = useState(false);
    const [showNotifySuccess, setNotifySuccess] = useState(false);

    const fetchDataReference = async () => {
        try {
            const requestData = {
                referenceTableName: 'system.process',
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

    const fetchDataUserSec = async () => {
        try {
            const requestData = {
                "idClient": loginData.clientId
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiUserSec/apiUserSec.php/listUserSec',
                requestData
            );

            setUserSec(responseData.data);
        } catch (error) {
            console.error(error);
        }
    };

   const fetchDataDepartment = async () => {
        try {
            const requestData = {
                "idClient": loginData.clientId
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
        fetchDataUserSec();
        fetchDataDepartment();
        fetchDataReference();
    }, []);

    const handleInsert = async () => {

        if (data.userSecId === '' || data.departmentId === '' || data.processName === '' || data.processOrder === '' || data.processDescription === '' || data._statusId === '') {
            handleShowError();
            return;
        }

        try {
           
            const requestData = {
                
                processId: data.processId,
                userSecId: data.userSecId,
                departmentId: data.departmentId,
                processName: data.processName,
                processOrder: data.processOrder,
                processDescription: data.processDescription,
                _statusId: data._statusId,
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiProcess/apiProcess.php/insertProcess',
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

    const handleChangeName = (event) => {
        setData({ ...data, processName: event.target.value });
    };

    const handleChangeDescription = (event) => {
        setData({ ...data, processDescription: event.target.value });
    };

    const handleChangeOrder = (event) => {
        setData({ ...data, processOrder: event.target.value });
    };

    const optionsUserSec = userSec.map((data) => ({
        value: data.user_sec_id,
        label: data.user_first_name + " " + data.user_last_name,
    }));

    const optionsReference = references.map((data) => ({
        value: data.reference_id,
        label: data.reference_name,
    }));

    const optionsDepartment = department.map((data) => ({
        value: data.department_id,
        label: data.department_name,
    }));

    const handleChangeUserSec = (event) => {
        setSelectedDataClient(event || '');
        if (event && event.value) {
            setData({ ...data, userSecId: event.value });
        }
    };

    const handleChangeDepartment = (event) => {
        setSelectedDataDepartment(event || '');
        if (event && event.value) {
            setData({ ...data, departmentId: event.value });
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
            title="Insertar nuevo proceso"
            body={
                <div className="card card-primary ">
                    <div className="card-header">
                        <h3 className="card-title">Datos del proceso</h3>
                        {showNotifyError && <Notify message="Todos los campos son obligatorios" type="error" />}
                        {showNotifySuccess && <Notify message="Datos guardados" type="success" />}
                    </div>
                    <div className="card-body ">
                        <form>
                            <div className="row">
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Id</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            disabled
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Responsable</label>
                                        <Select
                                            onChange={handleChangeUserSec}
                                            options={optionsUserSec}
                                            placeholder={'Seleccione un cliente'}
                                            value={selectedDataClient}
                                            isClearable
                                        >
                                        </Select>
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Nombre</label>
                                        <textarea
                                            type="text"
                                            className="form-control"
                                            placeholder="Enter ..."
                                            onChange={handleChangeName}
                                            style={{ height: "150px" }}
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Descripción</label>
                                        <textarea
                                            type="text"
                                            className="form-control"
                                            placeholder="Enter ..."
                                            onChange={handleChangeDescription}
                                            style={{ height: "150px" }}
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Orden</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            placeholder="Enter ..."
                                            onChange={handleChangeOrder}
                                        />
                                    </div>
                                </div>
                                <div className="form-group col-sm-12">
                                    <label>Estado</label>
                                    <Select
                                        onChange={handleChangeStatus}
                                        options={optionsReference}
                                        placeholder={'Seleccione un estado'}
                                        defaultValue={selectedDataReference}
                                        isClearable
                                    ></Select>
                                </div>
                                <div className="form-group col-sm-12">
                                    <label>Departmento</label>
                                    <Select
                                        onChange={handleChangeDepartment}
                                        options={optionsDepartment}
                                        placeholder={'Seleccione un estado'}
                                        value={selectedDataDepartment}
                                        isClearable
                                    ></Select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            }
            button1Name="Cerrar"
            button2Name="Guardar Cambios"
            handleButton1Click={closeModalInsert}
            handleButton2Click={handleInsert}
            closeModalEdit={closeModalInsert}
        />
    )
}






