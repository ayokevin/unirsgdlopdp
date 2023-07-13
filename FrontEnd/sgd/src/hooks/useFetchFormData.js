export async function fetchFormData(url, formData, additionalOptions = {}) {
    try {
      const options = {
        method: 'POST',
        body: formData,
        ...additionalOptions,
      };
  
      const response = await fetch(url, options);
      if (!response.ok) {
        throw new Error('Error al enviar la solicitud');
      }
  
      const responseData = await response.text();
  
      return responseData;
    } catch (error) {
      console.error('Error:', error);
      throw error;
    }
}