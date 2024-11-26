import {Spin} from 'antd'
import styles from './Preloader.module.css'

const Preloader = () => (
	<div className={styles.preloaderContainer}>
		<Spin size="large" />
	</div>
)

export {
	Preloader,
}