import { React, useEffect, useState, useContext } from 'react'
import { fetchJson } from '../../../hooks/useFetchJson';
import Modal from '../../Modal/Modal';
import Select from 'react-select';
import { AuthContext } from '../../Context/AuthContext';
import { fetchFormData } from '../../../hooks/useFetchFormData';
import { Notify } from '../../Modal/Notify';

export const InsertDocProcess = ({selectedDataRow, closeModalInsert }) => {

    const { loginData } = useContext(AuthContext);
    const [selectedFileName, setSelectedFileName] = useState(null);
    const [selectedDataReference, setSelectedDataReference] = useState(null);
    const [selectedDataProcess, setselectedDataProcess] = useState(null);
    const [references, setReferences] = useState([]);
    const [process, setProcess] = useState([]);

    const [data, setData] = useState({
        _status: '',
        _article: '',
        _process: selectedDataRow.process_id,
        _action: '',
        fileName:''
    });
    const [showNotifyError, setNotifyError] = useState(false);
    const [showNotifySuccess, setNotifySuccess] = useState(false);

    const fetchDataReference = async () => {
        try {
            const requestData = {
                referenceTableName: 'system.file',
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

        let inputFile = document.getElementById('inputFile').files[0];

        if (data._status === '' || data._process === '' || data.fileName === '' || !inputFile) {
            handleShowError();
            return;
        }

        try {

            const formdata = new FormData();
            formdata.append('_status', data._status);
            formdata.append('_article', data._article);
            formdata.append('_process', data._process);
            formdata.append('_action', data._action);
            formdata.append('file', inputFile);
            formdata.append('fileName', data.fileName);

            const responseData = await fetchFormData(
                'http://localhost:3000/app/apiFile/apiFile.php/insertFile',
                formdata
            );

            handleShowSuccess();
            setTimeout(() => {
                closeModalInsert();
            }, 2000);

        } catch (error) {
            console.error(error);
        }
    };

    const optionsReference = references.map((data) => ({
        value: data.reference_id,
        label: data.reference_name,
    }));

    const handleChangeStatus = (event) => {
        setSelectedDataReference(event || '');
        if (event && event.value) {
            setData({ ...data, _status: event.value });
        }
    };

    const handleFileInputChange = (event) => {
        const file = event.target.files[0];
        setSelectedFileName(file ? file.name : '');
    };

    const handleChangeFileName = (event) => {
        setData({ ...data, fileName: event.target.value });
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
            title={`Insertar documento en proceso: ${selectedDataRow.process_name}`}
            body={
                <div className="card card-primary ">
                    <div className="card-header">
                        <h3 className="card-title">Datos del archivo</h3>
                        {showNotifyError && <Notify message="Todos los campos son obligatorios" type="error" />}
                        {showNotifySuccess && <Notify message="Datos guardados" type="success" />}
                    </div>
                    <div className="card-body ">
                        <form id="form">
                            <div className="row">
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Id Archivo</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            disabled
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Archivo</label>
                                        <div className="input-group">
                                            <div className="custom-file">
                                                <input
                                                    type="file"
                                                    className="custom-file-input"
                                                    id="inputFile"
                                                    onChange={handleFileInputChange}
                                                />
                                                <label className="custom-file-label">
                                                    {selectedFileName}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Nombre de archivo</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            placeholder="Nombre del archivo"
                                            onChange={handleChangeFileName}
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






