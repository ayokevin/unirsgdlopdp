import { React, useEffect, useState,useContext } from 'react'
import { fetchJson } from '../../hooks/useFetchJson';
import Modal from '../Modal/Modal';
import Select from 'react-select';
import { AuthContext } from '../Context/AuthContext';
import { Notify } from '../Modal/Notify';


export const InsertDepartment = ({ closeModalInsert }) => {
    const { loginData } = useContext(AuthContext);

    const [selectedDataReference, setSelectedDataReference] = useState(null);

    const [references, setReferences] = useState([]);
    const [data, setData] = useState({
        idClient: "",
        departmentName: "",
        fatherId: "",
        statusId: "",
    });

    const [showNotifyError, setNotifyError] = useState(false);
    const [showNotifySuccess, setNotifySuccess] = useState(false);

    const fetchDataReference = async () => {
        try {

            const requestData = {
                referenceTableName: 'common.department',
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

        if (data.departmentName === '' || data.fatherId === '' || data.statusId === '' ) {
            handleShowError();
            return;
        }

        try {

            const requestData = {
                idClient: loginData.clientId,
                nameDepartment: data.departmentName,
                fatherId: data.fatherId,
                _statusId: data.statusId,
            };
            const responseData = await fetchJson(
                'http://localhost:3000/app/apiDepartment/apiDepartment.php/insertDepartment',
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
        setData({ ...data, departmentName: event.target.value });
    };

    const handleChangePadre = (event) => {
        setData({ ...data, fatherId: event.target.value });
    };

    const handleChangeStatus = (event) => {
        setSelectedDataReference(event || '');
        if (event && event.value) {
            setData({ ...data, statusId: event.value });
        }
    }

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

    const optionsReference = references.map((data) => ({
        value: data.reference_id,
        label: data.reference_name,
    }));

    return (
        <Modal
            title="Crear nuevo cliente"
            body={
                <div className="card card-primary">
                    <div className="card-header">
                        <h3 className="card-title">Datos del cliente</h3>
                        {showNotifyError && <Notify message="Todos los campos son obligatorios" type="error" />}
                        {showNotifySuccess && <Notify message="Datos guardados" type="success" />}
                    </div>
                    <div className="card-body">
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
                                        <label>Nombre</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            placeholder="Enter ..."
                                            onChange={handleChangeName}
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Padre</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            placeholder="Enter ..."
                                            onChange={handleChangePadre}
                                        />
                                    </div>
                                </div>
                                <div className="form-group col-sm-12">
                                    <label>Estado</label>
                                    <Select
                                        onChange={handleChangeStatus}
                                        options={optionsReference}
                                        placeholder={'Seleccione un estado'}
                                        value={selectedDataReference}
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
            closeModalInsert={closeModalInsert}
        />
    )
}






