import {App} from 'app/App'
import 'app/styles/index.css'
import {createRoot} from 'react-dom/client'
import {Provider} from 'react-redux'
import {store} from 'shared/redux'

const root = document.getElementById('root')

if (root) {
	createRoot(root).render(
		<Provider store={store}>
			<App/>
		</Provider>,
	)
}