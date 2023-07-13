import { React, useEffect, useState } from 'react'
import { fetchJson } from '../../hooks/useFetchJson';
import Modal from '../Modal/Modal';
import Select from 'react-select';
import { Notify } from '../Modal/Notify';


export const UpdateClient = ({ selectedClient, closeModalUpdateClient }) => {

    const [showNotifyError, setNotifyError] = useState(false);
    const [showNotifySuccess, setNotifySuccess] = useState(false);
    const [selectedDataReference, setSelectedDataReference] = useState({ value: selectedClient.status_id, label: selectedClient.reference_name });
    const [references, setReferences] = useState([]);
    const [clientData, setClientData] = useState({
        nameClient: selectedClient.client_name,
        rucClient: selectedClient.client_ruc,
        statusId: selectedClient.status_id,
    });


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
            // Maneja el error de la manera que prefieras
        }
    };

    useEffect(() => {
        fetchDataReference();
    }, []);

    const handleUpdateClient = async () => {

        if (clientData.nameClient === '' || clientData.rucClient === '' || clientData.statusId === '') {
            handleShowError();
            return;
        }

        try {
            const requestData = {
                idClient: selectedClient.client_id,
                nameClient: clientData.nameClient,
                rucClient: clientData.rucClient,
                _statusId: clientData.statusId,
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiClient/apiClient.php/updateClient',
                requestData
            );

            handleShowSuccess();
            setTimeout(() => {
                closeModalUpdateClient();
            }, 2000);


        } catch (error) {
            console.error(error);
            // Maneja el error de la manera que prefieras
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

    const optionsReference = references.map((data) => ({
        value: data.reference_id,
        label: data.reference_name,
    }));

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
            title="Editar Cliente"
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
                                            defaultValue={selectedClient.client_id}
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
                                            defaultValue={selectedClient.client_name}
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
                                            defaultValue={selectedClient.client_ruc}
                                            placeholder="Ingresar RUC"
                                            onChange={handleChangeRuc}
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
            } // Aquí puedes pasar el contenido que desees
            button1Name="Cerrar"
            button2Name="Guardar Cambios"
            handleButton1Click={closeModalUpdateClient}
            handleButton2Click={handleUpdateClient}
            closeModalUpdateClient={closeModalUpdateClient}
        />
    )
}






