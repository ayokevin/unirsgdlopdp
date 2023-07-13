import { React, useEffect, useState } from 'react';
import { fetchJson } from '../../hooks/useFetchJson';
import Modal from '../Modal/Modal';
import { Notify } from '../Modal/Notify';
import Select from 'react-select';

export const InsertClient = ({ closeModalInsertClient }) => {
    const [references, setReferences] = useState([]);
    const [selectedDataReference, setSelectedDataReference] = useState(null);
    const [clientData, setClientData] = useState({
        nameClient: '',
        rucClient: '',
        statusId: '',
    });
    const [showNotifyError, setNotifyError] = useState(false);
    const [showNotifySuccess, setNotifySuccess] = useState(false);

    const fetchDataReference = async () => {
        try {
            const requestData = {
                referenceTableName: 'common.client',
                referenceField: 'client_status_id',
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

    const handleInsertClient = async () => {
        if (clientData.nameClient === '' || clientData.rucClient === '' || clientData.statusId === '') {
            handleShowError();
            return;
        }
        try {
            const requestData = {
                nameClient: clientData.nameClient,
                rucClient: clientData.rucClient,
                _statusId: clientData.statusId,
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiClient/apiClient.php/insertClient',
                requestData
            );

            handleShowSuccess();
            setTimeout(() => {
                closeModalInsertClient();
            }, 2000);

        } catch (error) {
            console.error(error);
        }
    };

    const handleChangeName = (event) => {
        setClientData({ ...clientData, nameClient: event.target.value });
    };

    const handleChangeRuc = (event) => {
        setClientData({ ...clientData, rucClient: event.target.value });
    };

    const handleChangeStatus = (event) => {
        setSelectedDataReference(event || '');
        if (event && event.value) {
            setClientData({ ...clientData, statusId: event.value });
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

    const optionsReference = references.map((data) => ({
        value: data.reference_id,
        label: data.reference_name,
    }));

    return (
        <Modal
            title="Insertar nuevo cliente"
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
                                        <label>Id Client</label>
                                        <input type="text" className="form-control" disabled />
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Nombre</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            placeholder="Ingresar nombre"
                                            onChange={handleChangeName}
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Ruc</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            placeholder="Ingresar RUC"
                                            onChange={handleChangeRuc}
                                            required
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
                                    ></Select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            }
            button1Name="Cerrar"
            button2Name="Guardar Cambios"
            handleButton1Click={closeModalInsertClient}
            handleButton2Click={handleInsertClient}
            closeModalInsertClient={closeModalInsertClient}
        />
    );
};
